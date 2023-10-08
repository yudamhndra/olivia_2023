<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantsResource;
use App\Models\plants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PlantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plants = plants::all();
        return response()->json ([
            'status' => true,
            'message' => 'Data tanaman berhasil ditampilkan',
            'data' => $plants
        ]);
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
        $validator = Validator::make($request->all(), [
            'plant_img' => 'required|string',
            'plant_name' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'disease' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $base64Data = $request->input('plant_img');
        $binaryData = base64_decode($base64Data);

        $num = plants::where('plant_name', $request->input('plant_name'))
                ->where('disease', $request->input('disease'))
                ->count() + 1;

        $filename = '';
        if ($request->input('disease')) {
            $filename = $request->input('plant_name') . '.' . $request->input('disease') . '.' . $num . '.png';
        } else {
            $filename = $request->input('plant_name') . '.healthy.' . $num . '.png';
        }

        // Simpan file gambar di server
        Storage::disk('public')->put('plant_images/' . $filename, $binaryData);


        $plant = new plants([
            'id_users' => $request->input('id_users'),
            'plant_img' => 'plant_images/' . $filename,
            'plant_name' => $request->input('plant_name'),
            'condition' => $request->input('condition'),
            'disease' => $request->input('disease'),
        ]);
        $plant->save();

        return response()->json([
            'status' => true,
            'message' => 'Gambar tanaman berhasil diunggah'
        ]);
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
