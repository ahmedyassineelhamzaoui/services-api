<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3',
            'project_picture' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $image=$request->file('project_picture');
        $fileimage=time().$image->getClientOriginalName();
        $image->move(public_path('/uploads/'),$fileimage);
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_picture' =>  $fileimage
        ]);
        return response()->json([
            'success' => 'project has been uploadeed succssfuly'
        ]);
    }

    public function updateProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:3'
        ]);
        $project=Project::find($request->id);
        if($request->hasFile('project_picture')){
            $image= $request->file('project_picture');
            $filename= time().$image->getClientOriginalName();
            $image->move(public_path('/uploads/'),$filename);
            $project->project_picture=$filename;
        }
        $project->title=$request->title;
        $project->description=$request->description;
        $project->update();
        return response()->json([
            'success' => 'project has been updated successfuly'
        ]);
    }

    public function deleteProject(Request $request)
    {
        $project = Project::where('id', $request->id)->delete();
        if($project){
            return response()->json([
                'success' => 'project has been deleted successfuly'
            ]);
        }
        return response()->json([
             'error' => 'project not found'
        ]);
        
    }
}
