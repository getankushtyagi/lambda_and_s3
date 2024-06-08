<?php

use App\Http\Controllers\ImageController;
use Aws\Exception\AwsException;
use Aws\Lambda\LambdaClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('upload', [ImageController::class, 'upload'])->name('upload');


Route::post('lambda', function (Request $request) {
    // Validate the request inputs
    $request->validate([
        'num1' => 'required|numeric',
        'num2' => 'required|numeric',
    ]);

    $num1 = $request->input('num1');
    $num2 = $request->input('num2');

    try {
        // Create a Lambda client
        $lambda = new LambdaClient([
            'region' => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        // dd($lambda);
        logger(json_encode($lambda));

        // Invoke the Lambda function
        $result = $lambda->invoke([
            'FunctionName' => 'MultiplyNumbers', // The name of your Lambda function
            'InvocationType' => 'RequestResponse',
            'Payload' => json_encode([
                'num1' => $num1,
                'num2' => $num2,
            ]),
        ]);

        // Get the response payload
        $payload = json_decode($result->get('Payload')->getContents(), true);

        return response()->json($payload);
    } catch (AwsException $e) {
        // Output error message if fails
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
});
