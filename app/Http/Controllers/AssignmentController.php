<?php

namespace App\Http\Controllers;

use App\Enums\AssignmentStatus;
use App\Enums\Role;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AssignmentResource::collection(
            Assignment::latest()->get()
        );
    }

    public function published()
    {
        return AssignmentResource::collection(
            Assignment::published()->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssignmentRequest $request)
    {
        $assignment = Assignment::create($request->validated());

        return $assignment;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Assignment::findOrFail($id);
    }

    public function showBySlug(Assignment $assignment)
    {
        if(
            ! $assignment->isPublished() &&
            ! auth()->check()
        ) {
            abort(404);
        }

        if(
            ! $assignment->isPublished() &&
            auth()->user()->role === Role::STUDENT->value
        ) {
            abort(404);
        }

        return new AssignmentResource($assignment->load('processingUserSubmission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAssignmentRequest $request, Assignment $assignment)
    {
        $assignment->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Assignment::find($id)->delete();

        return response()->json(null);
    }
}
