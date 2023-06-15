<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use App\Models\skinmodel;
use Illuminate\Http\Request;

class usercontroller extends Controller
{
    public function alldata(){
        $users = User::with('skinmodel')->get();
        return response()->json($users);
    }

    public function show($id){
        $user = User::with('skinmodel')->find($id);
        return response()->json($user);
    }

    public function usersonly(){
        $only = User::all();
        return response()->json($only);
    }

    public function add(Request $req){
        $newUser=new User;
        $newUser->name=$req->name;
        $newUser->email=$req->email;
        $newUser->password=$req->password;
        $newUser->age=$req->age;
        $newUser->province=$req->province;
        $newUser->city=$req->city;
        $result=$newUser->save();
        if ('result'){
            return ["result"=>"data has been saved"];
        }
        else{
            return ["result"=>"canceled"];
        }
    }

    public function deleteuser($id){
        $deleteUser=User::find($id);
        $result=$deleteUser->delete();
        if($result){
            return ["result"=>"record has been deleted"];
        }
        else {
            return ["result"=>"failed"];
        }
    }

    public function edit(Request $req){
        $editUser = User::find($req->id);
        if ($req->has('name')) {
            $editUser->name = $req->name;
        }
        $editUser->email = $req->email;
        if ($req->has('email')) {
            $editUser->email = $req->email;
        }
        $editUser->password = $req->password;
        if ($req->has('password')) {
            $editUser->password = $req->password;
        }
        $editUser->age = $req->age;
        if ($req->has('age')) {
            $editUser->age = $req->age;
        }
        $editUser->province = $req->province;
        if ($req->has('province')) {
            $editUser->province = $req->province;
        }
        $editUser->city = $req->city;
        if ($req->has('city')) {
            $editUser->city = $req->city;
        }
        $result = $editUser->save();
        if($result){
            return ["result" => "record has been updated"];
        }
        else {
            return ["result" => "failed"];
        }
    }




}
