<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
//***** UNSECURED SITE ROUTES *****///
Route::group(array(),function(){
    Route::get('/', 'HomeController@showHome');
    Route::get('/authenticate', 'AuthController@authenticate');
    Route::get('/notAuthorized','AuthController@notAuthorized');
    Route::get('/kiosk', array('as'=> 'kiosk.showKiosk', 'uses'=>'KioskController@showKiosk'));
    Route::post('/kiosk',array('as'=>'kiosk.pickupPackage', 'before'=>'csrf','uses'=>'KioskController@showPackagePickup'));
    Route::post('/completePacakgePickup',array('as'=>'kiosk.completePackagePickup', 'before'=>'csrf','uses'=>'KioskController@completePackagePickup'));
});

//***** Authorized User Routes ******//
Route::group(array('before'=>'iit_user'),function(){
    Route::get('/dashboard', array('as' => 'dashboard', 'uses' =>'HomeController@showDashboard'));
    Route::get('/logout',array('as' => 'logout', 'uses' =>'HomeController@logout'));
    Route::get('/help',array('as' => 'help', 'uses' =>'HomeController@showHelp'));
    //Project Route group
    Route::group(array('prefix' => 'project'), function(){
        Route::get('{id}', 'ProjectController@Index');
        Route::get('{id}/orders/new', 'OrderController@newOrder'); 
        Route::post('{id}/orders/new','OrderController@newOrderProcess');
        Route::get('{projectid}/orders/{orderid}',array("as"=>"project.order.view","uses"=>"OrderController@viewOrder"))->where(array('projectid' => '[0-9]+'))->where(array('orderid' => '[0-9]+'));
    });
    
    Route::group(array('prefix'=>'api'), function(){
        Route::get('/userByCwid/{projectid}/{cwid}','AjaxApiController@userByCwid')->where(array('projectid' => '[0-9]+'));
    });
    
});

/*
     * IPRO Day routes
     */
    Route::group(array('prefix'=> 'iproday'),function(){
        /*
        * IPRO Day registration
        */
        Route::group(array('prefix'=>'registration'),function(){
           Route::get('/','IPRODayRegistrationController@index');
           Route::get('/{id}', 'IPRODayRegistrationController@showRegistration')->where(array('id' => '[0-9]+'));
           Route::post('/{id}', 'IPRODayRegistrationController@register')->where(array('id' => '[0-9]+'));
        });
    });


//**** Admin Routes *****//
Route::group(array('prefix' => 'admin', 'before'=>'auth_admin'), function(){
    
    Route::group(array('prefix'=>'orders'), function(){
        //admin/orders group
        Route::get('/', 'AdminOrderController@index');
        Route::get('/{id}','AdminOrderController@manage')->where(array('id' => '[0-9]+'));
        Route::post('/{id}/CreateNote',array('before'=>'csrf','as'=>'admin.order.createNote','uses'=>'AdminOrderController@createNote'));
        Route::group(array('prefix'=>'pickup'), function(){
            //admin/orders/pickup route group
            Route::get('/', array('as'=>'admin.order.pickup','uses'=>'AdminPickupController@index'));
            Route::post('/search', array('as'=>'admin.order.pickup.search', 'uses'=>'AdminPickupController@search'));
            Route::get('/viewItems',array('as'=>'admin.order.pickup.viewItems', 'uses'=>'AdminPickupController@viewItems'));
            Route::post('/createPickup', array('as'=>'admin.order.pickup.createPickup','uses'=>'AdminPickupController@createPickup'));
            Route::get('/show/{id}', array('as'=>'admin.order.pickup.showCode','uses'=>'AdminPickupController@viewPickup'))->where(array('id' => '[0-9]+'));
            Route::post("/override/{id}", array("as"=>"admin.order.pickup.override","uses"=>"AdminPickupController@overridePickup"))->where(array('id' => '[0-9]+'));
            Route::post("/process/{id}", array("as"=>"admin.order.pickup.process","uses"=>"AdminPickupController@processPickup"))->where(array('id' => '[0-9]+'));
            Route::post("/confirm/{id}", array("as"=>"admin.order.pickup.confirm","uses"=>"AdminPickupController@confirmPickup"))->where(array('id' => '[0-9]+'));
            Route::post("/redo/{id}", array("as"=>"admin.order.pickup.redo","uses"=>"AdminPickupController@redoPickup"))->where(array('id' => '[0-9]+'));
        });
    });
    
    Route::group(array('prefix'=>'items'),function(){
        //admin/items group
        Route::post('{id}/edit',array('before'=>'csrf','as'=>'admin.item.edit', 'uses'=>'AdminItemController@editProcess'));
        Route::post('/edit',array('before'=>'csrf','as'=>'admin.items.edit', 'uses'=>'AdminItemController@massEditProcess'));
        Route::post('{id}/statusChange',array('before'=>'csrf','as'=>'admin.item.statusChange', 'uses'=>'AdminItemController@statusChangeProcess'));
        Route::post('/statusChange',array('before'=>'csrf','as'=>'admin.items.statusChange', 'uses'=>'AdminItemController@massStatusChangeProcess'));
        Route::get('{id}/markReturning', 'AdminItemController@markItemReturning');
        Route::get('{id}/markNotReturning', 'AdminItemController@markItemNotReturning');
        Route::post('/markReturning',array('before'=>'csrf','as'=>'admin.items.markReturning', 'uses'=>'AdminItemController@massMarkReturningProcess'));
        Route::post('{id}/delete',array('before'=>'csrf','as'=>'admin.item.delete', 'uses'=>'AdminItemController@deleteItem'));
        Route::post('printLabel',array('before'=>'csrf','as'=>'admin.items.printLabels', 'uses'=>'AdminItemController@printLabels'));
    });
    Route::group(array('prefix'=>'projects'),function(){
        Route::get('/{id?}','AdminProjectController@index')->where(array('id' => '[0-9]+'));
        Route::get('/new','AdminProjectController@create');
        Route::post('/new','AdminProjectController@createProcess');
        Route::get('/edit/{id}','AdminProjectController@edit');
        Route::post('/edit/{id}',array('before'=>'csrf','uses'=>'AdminProjectController@editProcess'));
        Route::get('/overview/{id}','AdminProjectController@overview');
        
    });
    Route::group(array('prefix'=>'semesters'), function(){
        Route::get('/','AdminSemesterController@index');
        Route::get('/new','AdminSemesterController@create');
        Route::post('/new', array('before'=>'csrf','uses'=>'AdminSemesterController@createProcess'));
        Route::get('/edit/{id}',array('as'=>'admin.semesters.edit','uses'=>'AdminSemesterController@edit'));
        Route::post('/edit/{id}',array('as'=>'admin.semesters.edit','before'=>'csrf','uses'=>'AdminSemesterController@editProcess'));
        Route::get('/delete/{id}','AdminSemesterController@delete');
        Route::get('/makeActive/{id}', 'AdminSemesterController@makeActive');
    });
        
    Route::group(array('prefix'=>'iproday'), function(){
        //Generic controller for showing a dashboard page
        Route::get('/','AdminIPRODayController@index');
        Route::group(array('prefix'=>'{id}','where'=>array('id' => '[0-9]+')),function(){
            //Reporting route
            Route::get('/report/{report}','AdminIPRODayController@reporting');
        });
        
    });

        Route::group(array('prefix'=>'budgets'), function(){
            Route::get('/',array('as' => 'admin_budgets','uses' => 'AdminBudgetController@index'));
            Route::get('{id}/view','AdminBudgetController@viewBudget')->where(array('id' => '[0-9]+'));
            //Inside of budgets we have two different "Routes", Requests and actual approved budgets
            Route::group(array('prefix'=>'requests'), function(){
                Route::get('{id}/view',array('as'=>'admin.budget.viewRequest','uses'=>'AdminBudgetController@viewRequest'))->where(array('id' => '[0-9]+'));
                Route::post('approve',array('as'=>'admin.budget.approve','before'=>'csrf','uses'=>'AdminBudgetController@approve'));
                Route::post('deny',array('as'=>'admin.budget.deny','before'=>'csrf','uses'=>'AdminBudgetController@deny'));
            });
        });
        
        Route::group(array('prefix'=>'accounts'), function(){
            Route::get('/editor/{id}','AdminAccountController@showGLEditor')->where(array('id'=> '[0-9]+'));
            Route::post('/editor/{id}','AdminAccountController@newGLEntry')->where(array('id'=>'[0-9]+'));
        });
});