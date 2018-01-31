<?php

namespace App\Http\Controllers;

use App\Image;
use App\Libraries\Constant;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    const STORAGE_PATH = 'public';

    public function register(Request $request)
    {
        $json_request = json_decode($request->data);
        $validator = $this->validateEntry(json_decode($request->data, true));
        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->messages());
        } else {
            $role = Role::where('label', 'USER')->first();
            $user = new User();
            $user->first_name = $json_request->first_name;
            $user->last_name = $json_request->last_name;
            $user->phone = $json_request->phone;
            $user->password = sha1($json_request->password);
            $user->email = $json_request->email;
            if ($role->users()->save($user)) {
                if ($request->hasFile('profile_image')) {
                    $this->attachProfileImage($user, $request->allFiles());
                    return $this->sendSuccessResponse($user);
                }
            } else {
                return $this->sendErrorResponse(Constant::FAILED_REGISTRATION);
            }
        }
    }

    protected function attachProfileImage($user, $files)
    {
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $image_name = $file->getFilename().time().'.'.$extension;
            if (Storage::disk('public')->put($image_name, File::get($file))) {
                $image = new Image();
                $image->label = env('APP_URL') . '/uploads/' . $image_name;
                $image->user_id = $user->id;
                $image->save();
            }
        }
    }

    public function authenticate(Request $request)
    {
        $user = User::where(['email'=>$request->email, 'password' => sha1($request->password)])
                        ->with('role')->first();
        if ($user) {
            $token = JWTAuth::fromUser($user);
            $user->token = $token;
            return $this->sendSuccessResponse($user);
        } else {
            return $this->sendErrorResponse(Constant::INCORRECT_CREDENTIALS);
        }
    }

    protected function validateEntry(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|min:6|max:255|unique:users',
            'password' => 'required|string|min:5',
            'phone' => 'required|min:11|max:14|unique:users'
        ]);
    }
}
