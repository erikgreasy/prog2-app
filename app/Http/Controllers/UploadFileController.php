<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function __invoke(Request $request)
    {
        $file = $request->file('file');

        $path = $file->storeAs('', $file->getClientOriginalName(), 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'url' => asset($path)
            ]
        ]);
    }
}
