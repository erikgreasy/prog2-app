<?php

namespace App\Http\Controllers;

use App\Enums\AssignmentStatus;
use App\Enums\Role;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Models\Material;
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
        return Assignment::latest()->get();
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
        $validated = $request->safe()->except('materials');
        
        $assignment = Assignment::create($validated);

        collect($request->validated('materials'))->each(function (array $material) use ($assignment) {
            $assignment->materials()->create([
                'src' => $material['src']
            ]);
        });

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

        return new AssignmentResource($assignment);
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
        $existingMaterialsToKeep = collect($request->validated('materials'))->filter(fn(array $item) => isset($item['id']))->pluck('id');

        $assignment->materials->each(function (Material $material) use ($existingMaterialsToKeep) {
            if (!$existingMaterialsToKeep->contains($material->id)) {
                $material->delete();
            }
        });

        collect($request->validated('materials'))->each(function (array $material) use ($assignment) {
            if (isset($material['id'])) {
                Material::find($material['id'])->update([
                    'src' => $material['src']
                ]);
            } else {
                $assignment->materials()->create([
                    'src' => $material['src']
                ]);
            }
        });

        $validated = $request->safe()->except('materials');

        if (!$assignment->published_at && $request->validated('status') === AssignmentStatus::PUBLISH->value) {
            $validated += ['published_at' => now()];
        }
        
        $assignment->update($validated);
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
