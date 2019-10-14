<?php

namespace App\Api\V1\Controllers;

use DB;
//use App\Company;
//use Dingo\Api\Routing\Helpers;
use JWTAuth;
use App\Company;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
        if(!$request->page) {
          $companies = DB::table('companies')->orderBy('name', 'asc')->get();
          return $companies;
        }
        $companies = DB::table('companies')->orderBy('name', 'asc')->paginate($request->perPage);
        return $companies;
    }

    public function show($id)
    {
        return Company::find($id);
    }

    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser->role !== 'Admin') return abort('403');

        $requestData = $request->all();
        $requestData['logo'] = Storage::disk('public')->url('temp/'.$request->logo);
        $requestData['logo'] = str_replace('temp/', 'logos/', $requestData['logo']);
        $requestData['logo'] = config('app.url').'/'.$requestData['logo'];
        $created = Company::create($requestData);
        if($created) {
          Storage::disk('public')->move('temp/'.$request->logo, 'logos/'.$request->logo);
        }
        return $created;
    }

    public function update(Request $request, $id)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser->role === 'Manager' && !in_array($id, $currentUser->companies->modelKeys())) return abort('403');

      $company = Company::findOrFail($id);
      $requestData = $request->all();
      $requestData['logo'] = Storage::disk('public')->url('temp/'.$request->logo);
      $requestData['logo'] = str_replace('temp/', 'logos/', $requestData['logo']);
      $requestData['logo'] = config('app.url').'/'.$requestData['logo'];
      $updated = $company->update($requestData);

      if($updated) {
        Storage::disk('public')->move('temp/'.$request->logo, 'logos/'.$request->logo);
      }

      return $company;
    }

    public function destroy(Request $request)
    {
      $currentUser = JWTAuth::parseToken()->authenticate();
      if($currentUser->role !== 'Admin') return abort('403');

      $company = Company::findOrFail($request->id);
      $company->delete();

      return 204;
    }
}
