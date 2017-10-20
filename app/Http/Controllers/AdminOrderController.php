<?php

class AdminOrderController extends Controller {

    //Default route is to view all orders
    public function index(){
        //find the active semester
        $filters = array();
        if(Input::get("ipro") != ''){
            $filters['ipro'] = intval(Input::get("ipro"));
        }
        if( Input::get("status") != ''){
            $filters['status'] = intval(Input::get("status"));
        }
        if( Input::get("itemstatus") != ''){
            $filters['itemstatus'] = intval(Input::get("itemstatus"));
        }
        if(Input::get("semester") != ''){
            $filters['semester'] = Input::get("semester");
        }
        $activeSemester = null;
        if(array_key_exists('semester',$filters)){
            $activeSemester = Semester::where('id','=',$filters['semester'])->limit(1)->lists('id');
        }else{
            $activeSemester = Semester::where('Active','=',true)->limit(1)->lists('id');
        }
        if(count($activeSemester) == 0){
            return Redirect::route('admin.orders')->with('error',array('The filtered semester does not exist'));
        }


        //Get the projects in the semester
        $ipros = Project::where('Semester','=',$activeSemester[0])->get();
        View::share("ipros",$ipros);
        $statuses = DB::table("orderStatus")->orderBy("id")->lists("id","status");
        View::share("orderstatuses", $statuses);
        $itemstatuses = DB::table("itemStatus")->orderBy("id")->lists("id","status");
        View::share("itemstatuses", $itemstatuses);
        $semesters = DB::table("semesters")->orderBy("id","desc")->lists("id","name");
        View::share("semesters",$semesters);
        $projects = Project::where('Semester','=',$activeSemester[0])->lists('id');

        if(empty($projects)){
            $orders = array();
            View::share('orders',$orders);
        }else{
            //We need to get the orders from this semester and either apply filters or apply our default filter
            $orders = null;

            if(count($filters) != 0){
                $orders = Order::whereIn('ClassID',$projects);
                //Apply our filters
                //Apply the item status filter
                if(array_key_exists('itemstatus',$filters)){
                    $ordersWithStatus = Item::where('status','=',$filters['itemstatus'])->lists('OrderID');//Get items that have the speicfic status, returns an array of ordersIDs that have that order
                    if(count($ordersWithStatus) == 0){
                        $ordersWithStatus = array("-1");
                    }
                    $orders = $orders->whereIn('id',$ordersWithStatus);
                }
                if(array_key_exists('ipro',$filters)){
                    //apply the IPRO filter
                    $orders = $orders->where('ClassID','=',$filters['ipro']);
                }
                if(array_key_exists('status',$filters)){
                    $orders = $orders->where('status','=',$filters['status']);
                }

                $orders = $orders->orderBy('id','desc')->get();
            }else{
                $orders = Order::whereIn('ClassID',$projects)->where('status','!=',4)->orderBy('status')->orderBy('id','desc')->get();
            }

            View::share('orders',$orders);

        }
        View::share("filters",$filters);

        return View::make('admin.orders.index');
    }
    
    public function manage($id){
        //On this page we are managing only 1 order
        $order = Order::find($id);
        $user = $order->User()->first();
        $project = $order->Project()->first();
        $account = $project->Account()->first();
        $budgets = $account->Budgets()->get();
        $items = $order->Items()->get();
        $notes = $order->Notes()->get();
        $itemStatuses = DB::Table('itemStatus')->get();
        View::share('order_user',$user);
        View::share('order',$order);
        View::share('items',$items);
        View::share('account',$account);
        View::share('budgets',$budgets);
        View::share('notes',$notes);
        View::share('project',$project);
        View::share('itemStatuses',$itemStatuses);
        return View::make('admin.orders.manage');
    }
    
    public function createNote($id){
        //Grab the order id and pull the order
        $orderNote = new OrderNote;
        $orderNote->OrderID = $id;
        if(Input::get('ItemID') != 0){
            $orderNote->ItemID = Input::get('ItemID');
        }
        $orderNote->Notes = Input::get('Note');
        $orderNote->EnteredBy = Auth::id();
        $orderNote->save();
        return Redirect::to('/admin/orders/'.$id)->with('success',array('Successfully created order note'));
    }
}