<?php

namespace App\Http\Controllers\Panel;

use App\Models\Period;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodResource;
use Illuminate\Support\Facades\Session;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        try {
            $periods = Period::query();

            if ($keyword = request('search')) {
                $periods =  $periods->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('description', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('language_id')) {
                $periods = $periods->whereLanguage_id($request->language_id);
            }

            return response()->json([
                'status' => true,
                'count' => $periods->get()->count(),
                'data' => PeriodResource::collection($periods->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
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
                'title' => ['required', 'string', 'max:255', 'unique:periods'],
                'description' => ['required'],
                'image' => ['required', 'string', 'max:255'],
                'language_id' => ['required', 'string', 'max:255'],
            ]);

            Period::create($data);

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
    public function destroy(Request $request, Period $period)
    {
        try {
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
