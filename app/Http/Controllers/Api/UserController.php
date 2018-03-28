<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Config;
use URL;
use Gate;
use App\User;
use App\Activity;
use Log;

class UserController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->validations = [
      'name'         => 'required|min:3|max:255',
      'about'        => 'max:350',
      'image'        => 'max:10000|mimes:jpeg,png',
      'password'     => 'sometimes|required|between:7,50',
    ];
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $user = $this->guard()->user();

    //1.- Only admins can get the list of users
    if($user->admin){
      $users = User::latest()->get();
      $total = User::count();

      return response()->json([
        'success'   => true,
        'meta'      => [
            'total' => $total
        ],
        'users'     => $users,
      ]);
    }
    
    return response()->json([
      'success'   => false,
      'errors'    => ['You don\'t have access to this resource'],
    ], 403);
  }

}