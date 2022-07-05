<?php

namespace App\Http\Controllers\Panel;

use App\Models\Part;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartResource;
use Illuminate\Support\Facades\Session;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        try {
            $parts = Part::query();

            if ($keyword = request('search')) {
                $parts =  $parts->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('description', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('lesson_id')) {
                $parts = $parts->whereLesson_id($request->lesson_id);
            }
            if ($keyword = request('lesson_title')) {
                $lesson = Lesson::whereTitle($keyword)->first();
                $parts = $parts->whereLessonId($lesson->id);
            }
            return response()->json([
                'status' => true,
                'count' => $parts->get()->count(),
                'data' => PartResource::collection($parts->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Part $part)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => new PartResource($part)
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
                'title' => ['required', 'string', 'max:255', 'unique:levels'],
                'description' => ['required'],
                'image' => ['required'],
                'language_id' => ['required', 'string', 'max:255'],
                'period_id' => ['required', 'string', 'max:255'],
                'level_id' => ['required', 'string', 'max:255'],
                'lesson_id' => ['required', 'string', 'max:255'],
            ]);
    
            if($request->hasvocab == "hasvocab"){
                $data['hasvocab'] = "1" ;
            }

            $media = $request->image;
            $path = URL::asset('storage/'.$media->store('images','public'));
            $data['image'] = $path;
    
            Part::create($data);
    
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Part  $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string', 'max:255',  Rule::unique('parts' , 'title')->ignore($part->id) ],
                'description' => ['required'],
                'image' => ['required'],
    
            ]);

            $media = $request->image;
            $path = is_file($request->image) ? (URL::asset('storage/'.$media->store('images','public'))) : $request->image;
            $data['image'] = $path;
    
            $part->update($data) ;

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
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Part $part)
    {
        $part->delete() ;

        return response()->json(['success' => 'حذف با موفقیت انجام شد']);
    }
}
