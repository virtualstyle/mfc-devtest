<?php

namespace App\Api\V1\Controllers;

use Auth;
use App\Http\Controllers\Controller;

use DB;
//use App\Company;
//use Dingo\Api\Routing\Helpers;
use JWTAuth;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    public function index(Request $request)
    {
        return User::with('companies')->get();

    }

    public function show($id)
    {
        return User::with('companies')->find($id);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'Manager';
        $user->password = $request->password;
        $user->save();
        $user->companies()->attach($request->companies);
        return $user;
    }

    public function update(Request $request, $id)
    {
      $user = User::findOrFail($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->role = 'Manager';
      $user->password = $request->password;
      $user->save();
      $user->companies()->attach($request->companies);

      return $user;
    }

    public function destroy(Request $request)
    {
      $user = User::findOrFail($request->id);
      $user->companies()->detach();
      $user->delete();

      return 204;
    }

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
        $currentUser = JWTAuth::parseToken()->authenticate();
        if($currentUser->role !== 'Admin') return abort('403');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }
}
