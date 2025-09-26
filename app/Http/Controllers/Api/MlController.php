<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\TrainMlModel;
use App\Jobs\ProcessMlPrediction;

class MlController extends Controller
{
    public function train(Request $request)
    {
        // Dispatch job to train ML model
        TrainMlModel::dispatch();
        
        return response()->json([
            'message' => 'Training started successfully',
            'status' => 'processing'
        ]);
    }

    public function status(Request $request)
    {
        // Return status of ML training/prediction
        // In a real implementation, you would check the actual status
        return response()->json([
            'status' => 'ready', // or 'processing', 'completed', 'failed'
            'message' => 'ML service is ready'
        ]);
    }

    public function predict(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store the uploaded image
        $path = $request->file('foto')->store('ml-predictions', 'public');
        
        // Dispatch job to process ML prediction
        ProcessMlPrediction::dispatch($path);
        
        return response()->json([
            'message' => 'Prediction started successfully',
            'status' => 'processing',
            'image_path' => $path
        ]);
    }
}
