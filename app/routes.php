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
});

//***** Authorized User Routes ******//
Route::group(array('before'=>'iit_user'),function(){
    Route::get('/dashboard', 'HomeController@showDashboard');
    Route::get('/logout','HomeController@logout');
    //Project Route group
    Route::group(array('prefix' => 'project'), function(){
        Route::get('{id}', 'ProjectController@Index');
        Route::get('{id}/orders/new', 'OrderController@newOrder'); 
        Route::post('{id}/orders/new','OrderController@newOrderProcess');
    });
    
    Route::group(array('prefix'=>'api'), function(){
        Route::get('/userByCwid/{projectid}/{cwid}','AjaxApiController@userByCwid')->where(array('projectid' => '[0-9]+'));
        
    });
    Route::group(array('prefix'=>'email_test'), function(){
        Route::get('order','EmailTestController@orderCreate');
        Route::get('pickup','EmailTestController@orderPickup');
        Route::get('ordered','EmailTestController@orderOrder');
        Route::get('complete','EmailTestController@orderComplete');
        Route::get('cancel','EmailTestController@orderCancel');
    });
    
});

//**** Admin Routes *****//
Route::group(array('prefix' => 'admin', 'before'=>'auth_admin'), function(){
    Route::get('dashboard','AdminController@dashboard');
    Route::group(array('prefix'=>'orders'), function(){
        //admin/orders group
        Route::get('/', 'AdminOrderController@index');
        Route::get('/{id}','AdminOrderController@manage');
        Route::post('/{id}/CreateNote',array('before'=>'csrf','as'=>'admin.order.createNote','uses'=>'AdminOrderController@createNote'));
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
        
    });
    Route::group(array('prefix'=>'projects'),function(){
        Route::get('/{id?}','AdminProjectController@index')->where(array('id' => '[0-9]+'));
        Route::get('/new','AdminProjectController@create');
        Route::post('/new','AdminProjectController@createProcess');
        Route::get('/edit/{id}','AdminProjectController@edit');
        Route::post('/edit/{id}',array('before'=>'csrf','uses'=>'AdminProjectController@editProcess'));
    });
    Route::group(array('prefix'=>'semesters'), function(){
        Route::get('/','AdminSemesterController@index');
        Route::get('new','AdminSemesterController@create');
        Route::post('new', array('before'=>'csrf','uses'=>'AdminSemesterController@createProcess'));
        Route::get('/edit/{id}',array('as'=>'admin.semesters.edit','uses'=>'AdminSemesterController@edit'));
        Route::post('/edit/{id}',array('as'=>'admin.semesters.edit','before'=>'csrf','uses'=>'AdminSemesterController@editProcess'));
        Route::get('/delete/{id}','AdminSemesterController@delete');
        Route::get('makeActive/{id}', 'AdminSemesterController@makeActive');
    });
});