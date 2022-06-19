<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Language;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Languagemother;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LanguageResource;
use App\Models\Slider;

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
           $check = $this->checkAnswer($request->slider_id,$request->answer);
           if($check == true){
            Performance::updateOrCreate(['user_id' => Auth::id(),'slider_id' => $request->slider_id],['answer' => 1,'score' => 1]);
           }else{
            Performance::updateOrCreate(['user_id' => Auth::id(),'slider_id' => $request->slider_id],['answer' => 1,'score' => 0]);
           } 
           return response()->json([
            'status' => true,
            'message' => '',
        ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function checkAnswer($slider,$answer)
    {
        try {
            $answers = Slider::find($slider)->sliderAnswers;
            foreach($answers as $ans){
                if($ans->answertext == $answer);
                return true;
            }  
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
