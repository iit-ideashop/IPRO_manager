<?php

class AdminSemesterController extends BaseController {
    public function index(){
        $semesters = Semester::all();
        View::share('semesters',$semesters);
        return View::make('admin.semesters.index');
    }

    public function create(){
       //We are going to create a new Semester
       return View::make('admin.semesters.create');
    }
    
    public function createProcess(){
        $semester = new Semester;
        $semester->Name = Input::get('Name');
        $semester->Active = 0;
        $semester->ActiveStart = Input::get('ActiveStart');
        $semester->ActiveEnd = Input::get('ActiveEnd');
        $semester->modifiedBy = Auth::id();
        
        
        if($semester->save()){
            //Save success!
            if(Input::get('makeActive') == 'yes'){
                $semester->makeActive();
            }
            return Redirect::to('/admin/semesters')->with('success',array('Semester Added Successfully'));
        }else{
            //We got errors
            return Redirect::to('/admin/semesters/new')->with('error',$semester->errors()->all());
        }
         
        
    }
    
    //Edit a semester, show the edit form
    public function edit($id){
        $semester = Semester::find($id);
        
        View::share('editSemester',$semester);
        return View::make('admin.semesters.edit');
    }
    //Process an edit
    public function editProcess($id){
        $semester = Semester::find($id);
        $semester->Name = Input::get('Name');
        $semester->ActiveStart = Input::get('ActiveStart');
        $semester->ActiveEnd = Input::get('ActiveEnd');
        $semester->modifiedBy = Auth::id();
        
        
        if($semester->save()){
            //Save success!
            return Redirect::to('/admin/semesters')->with('success',array('Semester Updated Successfully'));
        }else{
            //We got errors
            return Redirect::to('/admin/semesters/edit/'.$semester->id)->with('error',$semester->errors()->all());
        }
    }
    
    public function delete($id){
        $semester = Semester::find($id);
        //Check if the semester is active
        if($semester->Active){
            return Redirect::to('/admin/semesters')->with('error',array('Cannot delete active semester'));
        }
        if($semester->delete()){
            return Redirect::to('/admin/semesters')->with('success',array('Semester Deleted Successfully'));
        }else{
            return Redirect::to('/admin/semesters')->with('error',$semester->errors()->all());
        }
        
    }
    
    public function makeActive($id){
        $semester = Semester::find($id);
        $semester->makeActive();
        return Redirect::to('/admin/semesters')->with('success',array('Semester "'. $semester->Name.'" is now active!'));
    }
}
