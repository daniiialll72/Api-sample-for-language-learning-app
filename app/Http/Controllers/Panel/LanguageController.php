<?php

namespace App\Http\Controllers\Panel;

use App\Models\User;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Languagemother;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    // public function user_language_set(User $user)
    // {
    //     return view('panel.languages.languageuser' , compact('user')) ;

    // }


    public function index(Request $request)
    {

        try {
            $languages = Language::query();

            if ($keyword = request('search')) {

                $languages =  $languages->where(function ($query) use ($keyword) {
                    $query->where('description', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('shortdescription', 'LIKE', '%' . $keyword . '%')
                        ->Orwhere('explainlanguage', 'LIKE', '%' . $keyword . '%');
                });
            }
            if ($keyword = request('languagemother_id')) {
                $languages = $languages->whereLanguagemother_id($request->languagemother_id);
            }
            if ($keyword = request('languagemother_name')) {
                $languages = $languages->whereHas('languagemother', function($q) use ($keyword){
                    $q->where('description', $keyword);
                 });
            }
            return response()->json([
                'status' => true,
                'data' => LanguageResource::collection($languages->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
                'count' => LanguageResource::collection($languages->get())->count(),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Language $language)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => new LanguageResource($language)
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
                'shortdescription' => ['required', 'string', 'max:255'],
                'description' => ['required'],
                'explainlanguage' => ['required', 'string', 'max:255'],
                'image' => ['required','file'],
            ]);

            $media = $request->image;
            $path = URL::asset('storage/'.$media->store('images','public'));
            $data['image'] = $path;

            $data['languagemother_id'] = $request->languagemother_id;

            Language::create($data);

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
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {

        try {

            $data = $request->validate([
                'shortdescription' => ['required', 'string', 'max:255'],
                'description' => ['required'],
                'explainlanguage' => ['required', 'string'],
                'image' => ['required','file'],
            ]);

            $media = $request->image;
            $path = is_file($request->image) ? (URL::asset('storage/'.$media->store('images','public'))) : $request->image;
            $data['image'] = $path;

            $language->update($data);

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
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Language $language)
    {
        try {
            $language->delete();

            return response()->json(['success' => 'حذف با موفقیت انجام شد']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
