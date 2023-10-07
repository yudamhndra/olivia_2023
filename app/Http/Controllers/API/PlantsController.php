<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantsResource;
use App\Models\plants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plants = plants::all();
        return PlantsResource::collection($plants);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    public function store(Request $request)
    {
        $request->validate([
            'plant_img' => 'required|string', 
            'plant_name' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'disease' => 'nullable|string|max:255',
        ]);

        $plant = new plants([
            'id_users' => auth()->user()->id, 
            'plant_img' => $request->input('plant_img'), 
            'plant_name' => $request->input('plant_name'),
            'condition' => $request->input('condition'),
            'disease' => $request->input('disease'),
        ]);

        $plant->save();

        return response()->json(['message' => 'Gambar tanaman berhasil diunggah']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
