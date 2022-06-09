<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Languagemother;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguagemotherResource;

class LanguagemotherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = Languagemother::query();
            if ($request->input('id')) {
                $query = $query->where('id', $request->input('id'));
            }
            if ($request->input('search')) {
                $query = $query->where('shortdescription', 'LIKE', '%' . $request->input('search') . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->input('search') . '%');
            }
            return response()->json([
                'status' => true,
                'count' => $query->get()->count(),
                'data' => LanguagemotherResource::collection($query->paginate($request->input('per_page') ? $request->input('per_page') : 10)),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'shortdescription' => ['required', 'string', 'max:255', 'unique:areas'],
                'description' => ['required'],
                'image' => ['required', 'string', 'max:255'],
            ]);

            Languagemother::create($data);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Languagemother $languagemother)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => new LanguagemotherResource($languagemother)
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Languagemother $languagemother)
    {
        try {
            $languagemother->update($request->all());

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Languagemother $languagemother)
    {
        try {
            $languagemother->delete();

            return response()->json(['success' => 'حذف با موفقیت انجام شد']);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
