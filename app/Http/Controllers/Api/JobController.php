<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\JobResource;
use function Laravel\Prompts\search;


class JobController extends Controller
{
    public function index() {
        // Retrieve and return a list of jobs
        $jobs = Job::all();
        // return response()->json($jobs, 200);
        return JobResource::collection($jobs);
    }

    public function show($id) {
        // Retrieve and return a specific job by ID
        $job = Job::findOrFail($id);
        // return response()->json($job, 200);
        return new JobResource($job);

    }
    public function search(Request $request) {
        $search = $request->search;

        $jobs = Job::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        })->orWhereHas('skill', function ($query) use ($search) {
            $query->where('name', 'like', "%$search%");
        })->get();

        return $jobs;
    }



    public function store(Request $request) {

        $validator = Validator::make($request->all() ,[
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|integer',
            'location_id' => 'required|integer',
        ],[
            'name.required' => 'برجاء ادخال الاسم',
            'description.required' => 'برجاء كتابه الوصف',
            'status.required'=> 'هذا الحقل مطلوب',
            'user_id' => 'required|integer',
            'location_id' => 'required|integer',
        ]);
        if ($validator-> fails()){
            return response($validator->errors()->all() , 422);
        }

        $job = Job::create($request->all());
        return new JobResource($job);

    }

    public function update(Request $request,Job $job, $id) {
        $validator = Validator::make($request->all() ,[

            "name"=>"required",
            "description"=>"required",
            "status"=>"required",
            "user_id"=>"required",
            "location_id"=>"required",

        ] ,[
            'name.required' => 'الاسم لا يمكن ان يكون فارغاً',
            'description.required' => 'الوصف لا يمكن ان يكون فارغاً',
            'status.required'=> 'هذا الحقل مطلوب',
            'user_id' => 'required|integer',
            'location_id' => 'required|integer',
        ]);
        if ($validator-> fails()){
            return response($validator->errors()->all() , 422);
        }

        $job = Job::findOrFail($id);
        $job->update($request->all());

        return new JobResource($job);


    }


    public function destroy($id) {
        // Delete a specific job by ID
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(['تم المسح بنجاح!'], 200);
    }
}
