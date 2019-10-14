<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->file->store('temp', 'public');
      return response()
          ->json([
              'status' => 'ok',
              'filename' => $request->file->hashName(),
          ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $deleted=Storage::disk('public')->delete(str_replace('|', '/', $request->filename));
      return response()
          ->json([
              'status' => $deleted,
          ]);
    }
}
