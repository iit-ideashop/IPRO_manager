<?php

class AdminOrderController extends Controller {

    //Default route is to view all orders
    public function index(){
        //find the active semester
        $activeSemester = Semester::where('Active','=',true)->limit(1)->lists('id');
        
        //Get the projects in the semester
        $projects = Project::where('Semester','=',$activeSemester[0])->lists('id');
        //We need to get the orders from this semester which are active or on status 1
        $orders = Order::whereIn('ClassID',$projects)->where('status','=',1)->get();
        View::share('orders',$orders);
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