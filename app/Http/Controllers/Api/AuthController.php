<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\skinmodel;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\Exception\NotFoundException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /////////////////////REGIS///////////////////
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'age' => 'required|integer',
            'province' => 'required|string',
            'city' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'province' => $request->province,
                'city' => $request->city
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->tokens()->where('name', 'auth_token')->delete();

            DB::commit();

            return response()->json([
                'message' => 'User has been regist'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

//////////////LOGIN//////////////////////

public function login(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    $user = User::where('email', $request->email)->firstOrFail();
    $user->tokens()->where('name', 'auth_token')->delete();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login success',
        'access_token' => $token,
        'token_type' => 'Bearer'
    ]);
}

    //////////////////LOGOUT//////////////
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }
    //////////////////DELETEUSER/////////////
    public function deleteuser()
{
    $user = User::find(Auth::user()->id);

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    // Hapus semua token akses terkait dengan pengguna
    $user->tokens()->delete();

    if ($user->delete()) {
        return response()->json([
            'message' => 'User has been deleted'
        ], 200);
    }

    return response()->json([
        'message' => 'Failed to delete user'
    ], 500);
}
    /////////////EDIT USER/////////
    public function update($id, Request $req)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
    $editUser = User::find($req->id);
    if (!$editUser) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }
    if ($user->id !== $editUser->id) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
    if ($req->has('name')) {
        $editUser->name = $req->name;
    }
    if ($req->has('email')) {
        $editUser->email = $req->email;
    }
    if ($req->has('password')) {
        $editUser->password = $req->password;
    }
    if ($req->has('age')) {
        $editUser->age = $req->age;
    }
    if ($req->has('province')) {
        $editUser->province = $req->province;
    }
    if ($req->has('city')) {
        $editUser->city = $req->city;
    }
    $result = $editUser->save();
    if ($result) {
        return response()->json([
            'message' => 'Record has been updated'
        ], 200);
    } else {
        return response()->json([
            'message' => 'Failed to update record'
        ], 500);
    }
}

//////////////ADDSKIN/////////////////////


public function addSkin(Request $request)
{

    // Create a new instance of the StorageClient
    $storage = new StorageClient([
        'projectId' => 'projectscantion',
        'keyFilePath' => base_path() . '/keyfile.json',
    ]);
    // Get the bucket name
    $bucketName = 'scantionpicture';
    // Get the uploaded file
    $file = $request->file('link');
    // Generate a unique filename
    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
    // Upload the file to Google Cloud Storage
    $bucket = $storage->bucket($bucketName);
    $bucket->upload(
        $file->get(),
        [
            'name' => $filename,
        ]
    );
    // Return the public URL of the uploaded file
    $publicUrl = "https://storage.googleapis.com/{$bucketName}/{$filename}";

    $validator = Validator::make($request->all(), [
        'date' => 'required|date',
        'user_id' => 'required|string',
        'bodypart' => 'required|string',
        'since' => 'required|date',
        'symptom' => 'required|string',
        'cancertype' => 'required|string',
        'accu' => 'required|numeric',
        'link' => 'required|image'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $skin = skinmodel::create([
        'date' => $request->date,
        'user_id' => $request->user_id,
        'bodypart' => $request->bodypart,
        'since' => $request->since,
        'symptom' => $request->symptom,
        'cancertype' => $request->cancertype,
        'accu' => $request->accu,
        'link' => $publicUrl ?: 'default_link_value',
    ]);

    return response()->json([
        'message' => 'Skin added',
        'skin' => $skin,
    ], 200);
}
    ////////////addimage/////////////////////////
    public function store(Request $request)
    {
        // Validate the incoming request (e.g., file type, size, etc.)
        // $request->validate([
        //     'image' => 'required|image', // Assuming 'image' is the name of the input field
        // ]);

        // // Create a new instance of the StorageClient
        // $storage = new StorageClient([
        //     'projectId' => 'projectscantion',
        //     'keyFilePath' => base_path()."/keyfile.json",
        // ]);

        // // Get the bucket name
        // $bucketName = 'scantionpicture';

        // // Get the uploaded file
        // $file = $request->file('image');

        // // Generate a unique filename
        // $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        // // Upload the file to Google Cloud Storage
        // $bucket = $storage->bucket($bucketName);
        // $bucket->upload(
        //     $file->get(),
        //     [
        //         'name' => $filename,
        //     ]
        // );

        // // Return the public URL of the uploaded file
        // $publicUrl = "https://storage.googleapis.com/{$bucketName}/{$filename}";
        // return response()->json(['url' => $publicUrl]);
    }
    public function skinmodel()
    {
        $user = auth()->user(); // Mendapatkan objek pengguna terotentikasi saat ini
        $skinData = skinmodel::where('user_id', $user->id)->get(); // Mengambil data kulit pengguna terotentikasi saat ini

        return response()->json($skinData);
    }

}
