<?php

namespace App\Http\Controllers\Panel;

use App\Models\Tag;
use App\Models\Part;
use App\Models\Slider;
use App\Models\Slideranswer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Config;

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
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('kind')) {
                $sliders =  $sliders->where(function ($query) use ($keyword) {
                    $query->whereKind($keyword);
                });
            }
            if ($keyword = request('part_title')) {
                $part= Part::whereTitle($keyword)->first();
                $sliders = $sliders->wherePartId($part->id);
            }
            return response()->json([
                'status' => true,
                'count' => $sliders->get()->count(),
                'data' => SliderResource::collection($sliders->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Slider $slider)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => new SliderResource($slider)
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
                'image' => 'required|file',
                'voice' => 'required|file',
            ]);
            $image = $request->image;
            $image_path = is_file($request->image) ? (URL::asset('storage/'.$image->store('images','public'))) : $request->image;

            $voice= $request->voice;
            $voice_path = is_file($request->voice) ? (URL::asset('storage/'.$voice->store('voices','public'))) : $request->voice;

            $data['user_id'] = Auth::id();
            $data['type'] = serialize($request->type);
            $data['image'] = $image_path;
            $data['voice'] = $voice_path;

            $slider = Slider::create($data);

            if ($request->answers) {
                foreach ($request->answers as $answer) {
                    $slideranswer = new Slideranswer(
                        [
                        'answertext' => isset($answer['answerthisquestion']) ? $answer['answerthisquestion'] : '',
                        'image' => isset($answer['image']) ? (is_file($answer['image']) ? (URL::asset('storage/'.$answer['image']->store('images','public'))) : $answer['image']) : '' ,
                        'voice' => isset($answer['voice']) ? (is_file($answer['voice']) ? (URL::asset('storage/'.$answer['voice']->store('voice','public'))) : $answer['voice']) : '' ,
                    ]);

                    $slider->slideranswers()->save($slideranswer);

                    if($answer['selected'] === 'true'){
                        $slider->update(['answer' => $slideranswer->id]);
                    }
                }
            }
            if ($request->tags) {
                foreach ($request->tags as $tag) {
                    if(Tag::whereTag($tag)->first())
                    $slider->tags()->attach(Tag::whereTag($tag)->first()->id);
                }
            }

            return response()->json([
                'status' => true,
                'data' => new SliderResource($slider)
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
            $request->validate([
                'description' => 'required',
                'kind' => 'required',
                'part_id' => 'required',
                'image' => 'required',
                'voice' => 'required',
            ]);

            $image = $request->image;
            $image_path = is_file($request->image) ? (URL::asset('storage/'.$image->store('images','public'))) : $request->image;

            $voice= $request->voice;
            $voice_path = is_file($request->voice) ? (URL::asset('storage/'.$voice->store('voices','public'))) : $request->voice;

            $request->request->add([
                'user_id' => Auth::id(),
                'type' => serialize($request->type),
                'image' => $image_path,
                'voice' => $voice_path,
            ]);

            $slider->update($request->all());

            if ($request->answers) {
                $slider->slideranswers()->delete();
                foreach ($request->answers as $answer) {
                    $slideranswer =  $slider->slideranswers()->updateOrCreate([
                        'answertext' => isset($answer['answerthisquestion']) ? $answer['answerthisquestion'] : '',
                        'image' => isset($answer['image']) ? (is_file($answer['image']) ? (URL::asset('storage/'.$answer['image']->store('images','public'))) : $answer['image']) : '' ,
                        'voice' => isset($answer['voice']) ? (is_file($answer['voice']) ? (URL::asset('storage/'.$answer['voice']->store('voice','public'))) : $answer['voice']) : '' ,
                    ]);

                    if($answer['selected'] === 'true'){
                        $slider->update(['answer' => $slideranswer->id]);
                    }
                }
            }
            if ($request->tags) {
                $slider->tags()->detach();
                foreach ($request->tags as $tag) {
                    if(Tag::whereTag($tag)->first())
                    $slider->tags()->attach(Tag::whereTag($tag)->first()->id);
                }
            }
            return response()->json([
                'status' => true,
                'data' => new SliderResource($slider)
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
    public function destroy(Slider $slider)
    {
        try {
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

    public function handleOrder(Request $request)
    {
        try {
            $sliders = Slider::wherePart_id($request->part)->get();

            foreach ($sliders as $slider) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $slider->id) {
                        $slider->update(['order_slider' => $order['position']]);
                    }
                }
            }
            return response('Update Successfully.', 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
