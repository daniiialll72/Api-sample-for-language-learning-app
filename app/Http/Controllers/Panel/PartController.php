<?php

namespace App\Http\Controllers\panel;

use App\Models\Lesson;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
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
                'image' => ['required', 'string', 'max:255'],
                'language_id' => ['required', 'string', 'max:255'],
                'period_id' => ['required', 'string', 'max:255'],
                'level_id' => ['required', 'string', 'max:255'],
                'lesson_id' => ['required', 'string', 'max:255'],
            ]);
    
            if($request->hasvocab == "hasvocab"){
                $data['hasvocab'] = "1" ;
            }
    
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
                'image' => ['required', 'string', 'max:255'],
    
            ]);
    
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
    public function destroy(Request $request)
    {
        $part = Part::find($request->id) ;
        $part->delete() ;

        return response()->json(['success' => 'حذف با موفقیت انجام شد']);
    }
}
