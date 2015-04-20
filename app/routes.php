<?php
//***** ALL ROTUES MUST CONTAIN ROUTE NAMES TO WORK PROPERLY WITH ROUTING CODE IN OAUTH *****///

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
    Route::get('/', array('as'=>'home','uses'=>'HomeController@showHome'));
    Route::get('/authenticate', array('as'=>'authenticate','uses'=>'AuthController@authenticate'));
    Route::get('/notAuthorized',array('as'=>'unauthorized','uses'=>'AuthController@notAuthorized'));
    Route::get('/kiosk', array('as'=> 'kiosk.showKiosk', 'uses'=>'KioskController@showKiosk'));
    Route::post('/kiosk',array('as'=>'kiosk.pickupPackage', 'before'=>'csrf','uses'=>'KioskController@showPackagePickup'));
    Route::post('/completePacakgePickup',array('as'=>'kiosk.completePackagePickup', 'before'=>'csrf','uses'=>'KioskController@completePackagePickup'));
});

//***** IPRO DAY ROUTES *****///
Route::group(array('prefix'=> 'iproday'),function(){
    //***** IPRO DAY REGISTRATION *****///
    Route::group(array('prefix'=>'registration'),function(){
        Route::get('/',array('as'=>'iproday.registration','uses'=>'IPRODayRegistrationController@index'));
        Route::get('/{id}', array('as'=>'iproday.registration.showRegistration','uses'=>'IPRODayRegistrationController@showRegistration'))->where(array('id' => '[0-9]+'));
        Route::post('/{id}', array('as'=>'iproday.registration.processRegistration','uses'=>'IPRODayRegistrationController@register'))->where(array('id' => '[0-9]+'));
    });
});

//***** Authorized User Routes ******//
Route::group(array('before'=>'iit_user'),function(){
    Route::get('/dashboard', array('as' => 'dashboard', 'uses' =>'HomeController@showDashboard'));
    Route::get('/logout',array('as' => 'logout', 'uses' =>'HomeController@logout'));
    Route::get('/help',array('as' => 'help', 'uses' =>'HomeController@showHelp'));
    //Project Route group
    Route::group(array('prefix' => 'project', 'before'=>'project_enrolled'), function(){
        Route::get('{projectid}', array('as'=>'project.dashboard','uses'=>'ProjectController@Index'))->where(array('projectid' => '[0-9]+'));
        Route::get('{projectid}/orders', array('as'=>'project.orders', 'uses'=>'ProjectController@showOrders'))->where(array('projectid' => '[0-9]+'));
        Route::get('{projectid}/roster', array('as'=>'project.roster', 'uses'=>'ProjectController@showRoster'))->where(array('projectid' => '[0-9]+'));
        Route::get('{projectid}/orders/new',array('as'=>'project.order.new','uses'=>'OrderController@newOrder'))->where(array('projectid' => '[0-9]+'));
        Route::post('{projectid}/orders/new',array('as'=>'project.order.newProcess','uses'=>'OrderController@newOrderProcess'))->where(array('projectid' => '[0-9]+'));
        Route::get('{projectid}/orders/{orderid}',array("as"=>"project.order.view","uses"=>"OrderController@viewOrder"))->where(array('projectid' => '[0-9]+'))->where(array('orderid' => '[0-9]+'));
        Route::get('{projectid}/printSubmission',array("as"=>"project.printSubmission","uses"=>"ProjectController@printSubmission"))->where(array('projectid' => '[0-9]+'));
        Route::post('{projectid}/printSubmission/files',array("as"=>"project.printSubmission.files","uses"=>"ProjectController@printSubmissionUpload"))->where(array('projectid' => '[0-9]+'));
        Route::get('{projectid}/printSubmission/getfiles',array("as"=>"project.printSubmission.getfiles","uses"=>"ProjectController@getProjectFiles"))->where(array('projectid' => '[0-9]+'));
        Route::post('{projectid}/printSubmission/override',array("as"=>"project.printSubmission.override","uses"=>"ProjectController@overridePrintSubmission"))->where(array('projectid' => '[0-9]+'));
        //Group manager routes
        Route::get('{projectid}/groupmanager', array('as'=>'project.groupmanager','before'=>'project_instructor','uses'=>'ProjectController@groupManager'))->where(array('projectid' => '[0-9]+'));
        //Protected with project_instructor
        Route::group(array('prefix' => 'api','before'=>'project_instructor'), function(){
            Route::get('getGroups/{projectid}', array("as"=>"project.api.getGroups", "uses" => "ProjectAPIController@getGroups"))->where(array('projectid' => '[0-9]+'));
            Route::get('getStudents/{projectid}', array("as"=>"project.api.getStudents", "uses" => "ProjectAPIController@getStudents"))->where(array('projectid' => '[0-9]+'));
            Route::get('getAccountBalance/{projectid}', array("as"=>"project.api.getAccountBalance", "uses" => "ProjectAPIController@getAccountBalance"))->where(array('projectid' => '[0-9]+'));
            Route::post('addGroup/{projectid}', array("as"=>"project.api.addGroup", "uses" => "ProjectAPIController@addGroup"))->where(array('projectid' => '[0-9]+'));
            Route::post('removeGroup/{projectid}', array("as"=>"project.api.removeGroup", "uses" => "ProjectAPIController@removeGroup"))->where(array('projectid' => '[0-9]+'));
            Route::post('enrollStudent/{projectid}', array("as"=>"project.api.enrollStudent", "uses" => "ProjectAPIController@enrollStudent"))->where(array('projectid' => '[0-9]+'));
            Route::post('dropStudent/{projectid}', array("as"=>"project.api.dropStudent", "uses" => "ProjectAPIController@dropStudent"))->where(array('projectid' => '[0-9]+'));
            Route::post('transferFunds/{projectid}', array("as"=>"project.api.transferFunds", "uses" => "ProjectAPIController@transferFunds"))->where(array('projectid' => '[0-9]+'));
        });
    });

    //May have to be refactored to include all project API's in the same controller and namespace/route
    Route::group(array('prefix'=>'api'), function(){
        Route::get('/userByCwid/{projectid}/{cwid}',array('as'=>'api.userByCWID','uses'=>'AjaxApiController@userByCwid'))->where(array('projectid' => '[0-9]+'));
    });


    //**** Special Role Access Routes, Requires IIT login *****//
    Route::group(array('prefix'=>'printing'),function(){
        //File download and view routes which display or download the file to the user based on if they are printer, admin or group_member
        Route::get("/downloadFile/{fileid}", array("as"=>"printing.downloadfile","uses"=>"PrintingController@downloadFile"))->where(array('fileid' => '[0-9]+'));
        Route::get("/viewFile/{fileid}", array("as"=>"printing.viewfile","uses"=>"PrintingController@viewFile"))->where(array('fileid' => '[0-9]+'));
        Route::group(array('before'=>'role_printer'), function(){
            //Show the default printing page and determine where to redirect people
            Route::get("", array("as"=>"printing","uses"=>"PrintingController@index"));
            //These routes are for the printer and for admins
            Route::get("/awaitingPrint", array("as"=>"printing.awaitingPrint","uses"=>"PrintingController@awaitingPrint"));
            Route::get("/printed", array("as"=>"printing.printed","uses"=>"PrintingController@printed"));
            Route::group(array("prefix"=>"api"),function(){
                Route::post("/markPrinted", array("as"=>"printing.api.markPrinted","uses"=>"PrintingController@markPrinted"));
            });
        });

        Route::group(array('before'=>'auth_admin'), function(){
            //These routes are for admins only
            Route::get("/awaitingApproval", array("as"=>"printing.awaitingApproval","uses"=>"PrintingController@awaitingApproval"));
            Route::get("/checkin", array("as"=>"printing.checkin","uses"=>"PrintingController@checkInPosters"));
            Route::get("/posterPickup", array("as"=>"printing.posterpickup","uses"=>"PrintingController@posterPickup"));
            Route::get("/projectReport/{projectid?}", array("as"=>"printing.projectReport","uses"=>"PrintingController@projectReport"));
            Route::get("/printBarcode/{fileid}", array("as"=>"printing.printBarcode","uses"=>"PrintingController@printBarcode"))->where(array("fileid"=>'[0-9]+'));
            Route::post("/posterPickupSearch", array("as"=>"printing.pickup.search","uses"=>"PrintingController@userSearch"));
            Route::get("/studentPosterPickup", array("as"=>"printing.pickup","uses"=>"PrintingController@studentPosterPickup"));
            Route::post("/completePosterPickup", array("as"=>"printing.completePosterPickup","uses"=>"PrintingController@completeStudentPickup"));

            //**** Admin API routes ****//
            Route::group(array("prefix"=>"api"), function(){
                Route::post("approvePoster", array("as"=>"printing.api.approvePoster","uses"=>"PrintingController@approvePoster"));
                Route::post("receivePoster", array("as"=>"printing.api.receivePoster","uses"=>"PrintingController@receivePosterFromPrinter"));

            });
        });
    });
});




//**** Admin Routes *****//
Route::group(array('prefix' => 'admin', 'before'=>'auth_admin'), function(){
    Route::get("phpinfo", function(){
        phpinfo();
        exit;
    });

    Route::group(array('prefix'=>'orders'), function(){
        //admin/orders group
        Route::get('/',array('as'=>'admin.orders','uses'=>'AdminOrderController@index'));
        Route::get('/{id}',array('as'=>'admin.order.manage','uses'=>'AdminOrderController@manage'))->where(array('id' => '[0-9]+'));
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
        Route::get('{id}/markReturning', array('as'=>'admin.items.markReturning','uses'=>'AdminItemController@markItemReturning'));
        Route::get('{id}/markNotReturning', array('as'=>'admin.items.markNotReturning','uses'=>'AdminItemController@markItemNotReturning'));
        Route::post('/markReturning',array('before'=>'csrf','as'=>'admin.items.markReturning', 'uses'=>'AdminItemController@massMarkReturningProcess'));
        Route::post('{id}/delete',array('before'=>'csrf','as'=>'admin.item.delete', 'uses'=>'AdminItemController@deleteItem'));
        Route::post('printLabel',array('before'=>'csrf','as'=>'admin.items.printLabels', 'uses'=>'AdminItemController@printLabels'));
    });
    Route::group(array('prefix'=>'projects'),function(){
        Route::get('/{id?}',array('as'=>'admin.projects','uses'=>'AdminProjectController@index'))->where(array('id' => '[0-9]+'));
        Route::get('/new',array('as'=>'admin.projects.new','uses'=>'AdminProjectController@create'));
        Route::post('/new',array('as'=>'admin.projects.newProcess','uses'=>'AdminProjectController@createProcess'));
        Route::get('/edit/{id}',array('as'=>'admin.projects.edit','uses'=>'AdminProjectController@edit'));
        Route::post('/edit/{id}',array('before'=>'csrf','as'=>'admin.projects.editProcess','uses'=>'AdminProjectController@editProcess'));
        Route::get('/overview/{id}',array('as'=>'admin.projects.overview','uses'=>'AdminProjectController@overview'));
        Route::get('/enroll_users/{id}',array('as'=>'admin.projects.enrollUsers','uses'=>'AdminProjectController@enrollUsers'))->where(array('id' => '[0-9]+'));
        Route::get('/uploadCognos/{sem_id}', array('as'=>'admin.projects.uploadCognos', 'uses'=>'AdminProjectController@uploadCognos'))->where(array('sem_id' => '[0-9]+'));
        Route::post('/uploadCognos/{sem_id}',array('as'=>'admin.projects.uploadCognosProcess', 'uses'=>'AdminProjectController@uploadCognosProcess'))->where(array('sem_id' => '[0-9]+'));
    });
    Route::group(array('prefix'=>'semesters'), function(){
        Route::get('/',array('as'=>'admin.semesters','uses'=>'AdminSemesterController@index'));
        Route::get('/new',array('as'=>'admin.semester.create','uses'=>'AdminSemesterController@create'));
        Route::post('/new', array('before'=>'csrf','as'=>'admin.semesters.createProcess','uses'=>'AdminSemesterController@createProcess'));
        Route::get('/edit/{id}',array('as'=>'admin.semesters.edit','uses'=>'AdminSemesterController@edit'));
        Route::post('/edit/{id}',array('as'=>'admin.semesters.edit','before'=>'csrf','uses'=>'AdminSemesterController@editProcess'));
        Route::get('/delete/{id}',array('as'=>'admin.semesters.delete','uses'=>'AdminSemesterController@delete'));
        Route::get('/makeActive/{id}', array('as'=>'admin.semesters.makeActive','uses'=>'AdminSemesterController@makeActive'));
    });
        
    Route::group(array('prefix'=>'iproday'), function(){
        //Generic controller for showing a dashboard page
        Route::get('/',array('as'=>'admin.iproday','uses'=>'AdminIPRODayController@index'));
        Route::get('peoplesChoice',array('as'=>'admin.iproday.peopleschoice','uses'=>'AdminIPRODayController@peoplesChoice'));
        Route::get('peoplesChoice/terminal',array('as'=>'admin.iproday.peopleschoice.terminal','uses'=>'AdminIPRODayController@peoplesChoiceTerminal'));
<<<<<<< Updated upstream
=======

        Route::group(array("prefix"=>"api"),function(){
            Route::post("/validateUser", array("as"=>"admin.iproday.api.validateUser", "uses"=>"AdminIPRODayController@api_validateUser"));
        });
>>>>>>> Stashed changes
        Route::group(array('prefix'=>'{id}','where'=>array('id' => '[0-9]+')),function(){
            //Reporting route
            Route::get('/report/{report}',array('as'=>'admin.iproday.report','uses'=>'AdminIPRODayController@reporting'));
        });
        
    });

        Route::group(array('prefix'=>'budgets'), function(){
            Route::get('/',array('as' => 'admin_budgets','uses' => 'AdminBudgetController@index'));
            Route::get('{id}/view',array('as'=>'admin.budgets.view','uses'=>'AdminBudgetController@viewBudget'))->where(array('id' => '[0-9]+'));
            //Inside of budgets we have two different "Routes", Requests and actual approved budgets
            Route::group(array('prefix'=>'requests'), function(){
                Route::get('{id}/view',array('as'=>'admin.budget.viewRequest','uses'=>'AdminBudgetController@viewRequest'))->where(array('id' => '[0-9]+'));
                Route::post('approve',array('as'=>'admin.budget.approve','before'=>'csrf','uses'=>'AdminBudgetController@approve'));
                Route::post('deny',array('as'=>'admin.budget.deny','before'=>'csrf','uses'=>'AdminBudgetController@deny'));
            });
        });
        
        Route::group(array('prefix'=>'accounts'), function(){
            Route::get('/editor/{id}',array("as"=>"admin.accounts.editor", "uses"=>'AdminAccountController@showGLEditor'))->where(array('id'=> '[0-9]+'));
            Route::post('/editor/{id}',array('as'=>'admin.accounts.newGLEntry','uses'=>'AdminAccountController@newGLEntry'))->where(array('id'=>'[0-9]+'));
        });
});