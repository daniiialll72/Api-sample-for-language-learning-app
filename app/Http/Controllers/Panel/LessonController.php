<?php

namespace App\Http\Controllers\panel;

use App\Models\Lesson;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Http\Resources\LessonResource;
use Illuminate\Support\Facades\Session;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        try {
            $lessons = Lesson::query();

            if ($keyword = request('search')) {

                $lessons =  $lessons->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('description', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('level_id')) {

                $lessons = $lessons->whereLevel_id($request->level_id);
            }
            return response()->json([
                'status' => true,
                'data' => LessonResource::collection($lessons->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
                'count' => LessonResource::collection($lessons->paginate($request->input('per_page') ? $request->input('per_page') : 10))->count(),
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
                'period_id' => ['required', 'string', 'max:255'],
                'language_id' => ['required', 'string', 'max:255'],
                'level_id' => ['required', 'string', 'max:255'],
            ]);

            Lesson::create($data);

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
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string', 'max:255',  Rule::unique('lessons', 'title')->ignore($lesson->id)],
                'description' => ['required'],
                'image' => ['required', 'string', 'max:255'],
            ]);

            $lesson->update($data);

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
        $lesson = Lesson::find($request->id);
        $lesson->delete();

        return response()->json(['success' => 'حذف با موفقیت انجام شد']);
    }

    public function changeFreeStatus(Request $request)
    {

        try {
            $lesson = Lesson::find($request->id);

            $lesson->freeornot == '0' ? $lesson->update(['freeornot' => '1']) : $lesson->update(['freeornot' => '0']);

            return response()->json(['success' => ' با موفقیت انجام شد']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
