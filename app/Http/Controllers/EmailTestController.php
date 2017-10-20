<?php

class EmailTestController extends BaseController {
    
    public function orderCreate(){
        //Here we are testing an email, pull user #1, pull order #1, and load it up
        $order = Order::find(8);
        $person = $order->User()->first();
        $project = $order->Project();
        $items = $order->Items()->get();
        View::share('person',$person);
        View::share('order',$order);
        View::share('items',$items);
        return View::make('emails.orderCreate');
    }
    
    public function orderPickup(){
        //Here we are testing an email, pull user #1, pull order #1, and load it up
        $order = Order::find(8);
        $person = $order->User()->first();
        $project = $order->Project();
        $items = $order->Items()->get();
        View::share('person',$person);
        View::share('order',$order);
        View::share('items',$items);
        return View::make('emails.orderPickup');
    }
    
    public function orderOrder(){
        //Here we are testing an email, pull user #1, pull order #1, and load it up
        $order = Order::find(8);
        $person = $order->User()->first();
        $project = $order->Project();
        $items = $order->Items()->get();
        View::share('person',$person);
        View::share('order',$order);
        View::share('items',$items);
        return View::make('emails.orderOrdered');
    }
    public function orderComplete(){
        //Here we are testing an email, pull user #1, pull order #1, and load it up
        $order = Order::find(8);
        $person = $order->User()->first();
        $project = $order->Project();
        $items = $order->Items()->get();
        View::share('person',$person);
        View::share('order',$order);
        View::share('items',$items);
        return View::make('emails.orderComplete');
    }
    
    public function orderCancel(){
        //Here we are testing an email, pull user #1, pull order #1, and load it up
        $order = Order::find(8);
        $person = $order->User()->first();
        $project = $order->Project();
        $items = $order->Items()->get();
        View::share('person',$person);
        View::share('order',$order);
        View::share('items',$items);
        return View::make('emails.orderCancelled');
    }    
}
