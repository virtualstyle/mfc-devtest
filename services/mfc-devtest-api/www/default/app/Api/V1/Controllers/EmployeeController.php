<?php

namespace App\Api\V1\Controllers;

use DB;
//use App\Company;
//use Dingo\Api\Routing\Helpers;
use JWTAuth;
use App\Employee;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
        $employees = DB::table('employees')
          ->join('companies', 'companies.id', '=', 'employees.company')
          ->select('employees.*', 'companies.name')
          ->orderBy('last_name', 'asc')
          ->paginate($request->perPage);
        return $employees;
    }

    public function show($id)
    {
        return Employee::find($id);
    }

    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser->role === 'Manager' && !in_array($request->company, $currentUser->companies->modelKeys())) return abort('403');
        return Employee::create($request->all());
    }

    public function update(Request $request, $id)
    {
      $currentUser = JWTAuth::parseToken()->authenticate();
      if($currentUser->role === 'Manager' && !in_array($request->company, $currentUser->companies->modelKeys())) return abort('403');
      $employee = Employee::findOrFail($id);
      $employee->update($request->all());

      return $employee;
    }

    public function destroy(Request $request)
    {
      $currentUser = JWTAuth::parseToken()->authenticate();
      if($currentUser->role === 'Manager' && !in_array($request->company, $currentUser->companies->modelKeys())) return abort('403');
      $employee = Employee::findOrFail($request->id);
      $employee->delete();

      return 204;
    }
}
