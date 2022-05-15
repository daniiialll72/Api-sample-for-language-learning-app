<?php

namespace App\Http\Controllers\panel;

use App\Models\Part;
use App\Models\Slider;
use App\Models\Slideranswer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PeriodResource;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Session;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {

            $sliders = Slider::query();

            if ($keyword = request('search')) {

                $sliders =  $sliders->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('id', 'LIKE', '%' . $keyword . '%');
                });
            }

            return response()->json([
                'status' => true,
                'data' => SliderResource::collection($sliders->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function store(Request $request)
    {

        try {
            $data = $request->validate([

                'description' => 'required',
                'kind' => 'required',
                'part_id' => 'required',

            ]);

            $data['user_id'] = '31';
            $data['image'] = $request->image;
            $data['voice'] = $request->voice;
            $data['title'] = $request->title;

            $slider =  Slider::create($data);

            $check = 0;

            if ($request->answers) {

                foreach ($request->answers as $answer) {

                    $slideranswer = new Slideranswer(
                        [
                        'answertext' => isset($answer['answerthisquestion']) ? $answer['answerthisquestion'] : '',
                        'image' => isset($answer['image']) ? $answer['image'] : '' ,
                        'voice' => isset($answer['voice']) ? $answer['voice'] : '' ,
                    ]);

                    $slider->slideranswers()->save($slideranswer);

                    if($answer['selected'] === 'true'){
                        $slider->update(['answer' => $slideranswer->id]);
                    }
                }

                $check = 1;
            }

            if ($check == 0) {

                $slider->delete();
                return response()->json([
                    'status' => false,
                    'message' => 'Answers are empty'
                ], Response::HTTP_CREATED);
            }

            return response()->json([
                'status' => true,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Slider $slider)
    {

        try {
            $data = $request->validate([

                'description' => 'required',
                'kind' => 'required',
                'part_id' => 'required',

            ]);

            $data['user_id'] = '31';
            $data['image'] = $request->image;
            $data['voice'] = $request->voice;
            $data['title'] = $request->title;

            $slider->update($data);

            $check = 0;

            if ($request->answers) {

                foreach ($request->answers as $answer) {

                    $slider->slideranswers()->updateOrCreate([
                        'answertext' => isset($answer['answerthisquestion']) ? $answer['answerthisquestion'] : '',
                        'image' => isset($answer['image']) ? $answer['image'] : '' ,
                        'voice' => isset($answer['voice']) ? $answer['voice'] : '' ,
                    ]);

                    // if($answer['selected'] === 'true'){
                    //     $slider->update(['answer' => $slideranswer->id]);
                    // }
                }

                $check = 1;
            }

            if ($check == 0) {

                $slider->delete();
                return response()->json([
                    'status' => false,
                    'message' => 'Answers are empty'
                ], Response::HTTP_CREATED);
            }

            return response()->json([
                'status' => true,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $slider = Slider::find($request->id);
            $slider->slideranswers()->delete();
            $slider->delete();

            return response()->json(['success' => 'حذف با موفقیت انجام شد']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
