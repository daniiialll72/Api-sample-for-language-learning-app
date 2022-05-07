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
    public function storeSimpleSlider(Request $request)
    {

        try {
            $data = $request->validate([

                'description' => 'required',
                'kind' => 'required',
                'part_id' => 'required',

            ]);

            $data['user_id'] = Auth::id();
            $data['image'] = $request->image;
            $data['voice'] = $request->voice;
            $data['title'] = $request->title;

            Slider::create($data);

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

    public function storeMultiSlider(Request $request)
    {

        try {

            $data = $request->validate([

                'description' => 'required',
                'question' => 'required',
                'oriented' => 'required',
                'successmessage' => 'required',
                'failmessage' => 'required',
                'kind' => 'required',
                'part_id' => 'required',

            ]);

            $data['user_id'] = Auth::id();
            $data['image'] = $request->image;
            $data['voice'] = $request->voice;
            $data['title'] = $request->title;

            $slider =  Slider::create($data);

            $check = 0;

            if ($request->choice == "imagewithtext") {


                foreach ($request->answers as $answer) {

                    $slideranswer = new Slideranswer(
                        [
                        'answertext' => $answer['answerthisquestion'],
                        'image' => $answer['image'],
                    ]);

                    $slider->slideranswers()->save($slideranswer);
                }

                $check = 1;
            }
            if ($request->choice == "voicewithtext") {


                foreach ($request->answers as $answer) {

                    $slideranswer = new Slideranswer(
                        [
                        'answertext' => $answer['answerthisquestion'],
                        'voice' => $answer['voice'],
                    ]);

                    $slider->slideranswers()->save($slideranswer);
                }

                $check = 1;
            }
            if ($request->choice == "text") {


                foreach ($request->answers as $answer) {

                    $slideranswer = new Slideranswer(
                        [
                        'answertext' =>$answer['answerthisquestion'],
                    ]);

                    $slider->slideranswers()->save($slideranswer);
                }

                $check = 1;
            }

            if ($check == 0) {

                $slider->delete();
                return back()->withErrors(['msg' => 'میبایست یک جواب را وارد کنید']);
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

    public function update(Request $request, Period $period)
    {

        try {

            $data = $request->validate([
                'title' => ['required', 'string', 'max:255',  Rule::unique('periods', 'title')->ignore($period->id)],
                'description' => ['required'],
                'image' => ['required', 'string', 'max:255'],

            ]);

            $period->update($data);

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
            $period = Period::find($request->id);
            $period->delete();

            return response()->json(['success' => 'حذف با موفقیت انجام شد']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
