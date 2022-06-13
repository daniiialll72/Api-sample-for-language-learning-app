<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Languagemother;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function setAnswer(Request $request)
    {

        try {
          
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
