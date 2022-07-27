<?php

namespace App\Http\Controllers\Panel;

use App\Models\Level;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use Illuminate\Support\Facades\Session;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        try {
            $levels = Level::query();

            if ($keyword = request('search')) {

                $levels =  $levels->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('description', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('period_id')) {
                $levels = $levels->wherePeriod_id($request->period_id);
            }
            if ($keyword = request('period_title')) {
                $period = Period::whereTitle($keyword)->first();
                $levels = $levels->wherePeriodId($period->id);
            }
            return response()->json([
                'status' => true,
                'count' => $levels->get()->count(),
                'data' => LevelResource::collection($levels->paginate($request->input('per_page') ? $request->input('per_page') : 10))
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show(Level $level)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => new LevelResource($level)
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
                'description' => ['required', 'string', 'unique:levels'],
                'image' => ['required'],
                'period_id' => ['required', 'string', 'max:255'],
                'language_id'  => ['required', 'string', 'max:255']
            ]);

            $media = $request->image;
            $path = is_file($request->image) ? (URL::asset('storage/'.$media->store('images','public'))) : $request->image;
            $data['image'] = $path;

            $data['languagemother_id'] = $request->languagemother_id;

            Level::create($data);

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
    public function update(Request $request, Level $level)
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string', 'max:255',  Rule::unique('levels', 'title')->ignore($level->id)],
                'description' => ['required', 'string', Rule::unique('levels', 'description')->ignore($level->id)],
                'image' => ['required'],
    
            ]);

            $media = $request->image;
            $path = is_file($request->image) ? (URL::asset('storage/'.$media->store('images','public'))) : $request->image;
            $data['image'] = $path;
    
            $level->update($data);

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
    public function destroy(Request $request, Level $level)
    {
        $level->delete();

        return response()->json(['success' => 'حذف با موفقیت انجام شد']);
    }
}
