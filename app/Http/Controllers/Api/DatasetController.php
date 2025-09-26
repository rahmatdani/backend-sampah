<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatasetSampah;
use App\Http\Requests\DatasetRequest;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = DatasetSampah::with('pengguna')->get();
        return response()->json($datasets);
    }

    public function upload(DatasetRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dataset', 'public');
            $validated['path_file'] = $path;
        }

        $dataset = DatasetSampah::create($validated);

        // Load relationship
        $dataset->load('pengguna');

        return response()->json($dataset);
    }
}
