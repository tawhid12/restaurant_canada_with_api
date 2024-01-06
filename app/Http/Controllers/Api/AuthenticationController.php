<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ImageHandleTraits;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\Company;
use App\Models\User;

use Exception;
use Carbon\Carbon;
// Request
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\newUserRequest;


use Illuminate\Support\Str;

class AuthenticationController extends Controller
{

    use ResponseTrait, ImageHandleTraits;


    public function signIn(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        }

        $user = User::join('roles', 'roleId', '=', 'roles.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.userId')
            ->select("users.name", "users.username", "users.id as userId", "users.email", "users.mobileNumber", "roles.type as roleType", "roles.id as roleId", "user_details.photo", "roles.identity as roleIdentity")
            ->where(['users.mobileNumber' => $request->username, 'users.password' => md5($request->password), 'users.status' => 1])
            ->orWhere(function ($query) use ($request) {
                $query->where(['users.email' => $request->username, 'users.password' => md5($request->password), 'users.status' => 1]);
            })->first();

        if ($user) {
            $token = $this->tokenGen($user->userId);
            // Login as User
            return response()->json(array('success' => 1, 'token' => $token, 'user' => $user), 200);
        } else {
            return response()->json(array('errors' => [0 => 'Credentials Doesn\'t Match !']), 400);
        }
    }


    public function signUpregistrationStore(Request $request)
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'device_id'             => 'required|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        try {
            $lastCreatedUser = User::take(1)->orderBy('id', 'desc')->first();
            $user = new User;
            $user->roleId = 2;
            $user->name = $request->fullName;
            $user->email = $request->email;
            $user->mobileNumber = $request->mobileNumber;
            $user->password = md5($request->password);
            $user->status = 1;
            $user->userCreatorId = 1;
            $user->created_at = Carbon::now();
            $user->device_id = $request->device_id;

            if (!!$user->save()) {
                $token = $this->tokenGen($user->id);
                $userd = new UserDetail;
                $userd->userId = $user->id;

                if ($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');

                $userd->address = $request->address;
                if ($userd->save())
                    return response()->json(array('user' => $user, 'token' => $token), 200);
            }
        } catch (Exception $e) {
            return response()->json(array('errors' => [0 => 'Please try agian']), 400);
        }
    }

    public function signUp_delivery_boy_Store(Request $request)
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'device_id'             => 'required|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        try {
            $lastCreatedUser = User::take(1)->orderBy('id', 'desc')->first();
            $user = new User;
            $user->roleId = 4;
            $user->name = $request->fullName;
            $user->email = $request->email;
            $user->mobileNumber = $request->mobileNumber;
            $user->password = md5($request->password);
            $user->status = 1;
            $user->userCreatorId = 1;
            $user->created_at = Carbon::now();

            if (!!$user->save()) {
                $token = $this->tokenGen($user->id);
                $userd = new UserDetail;
                $userd->userId = $user->id;

                if ($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');

                $userd->address = $request->address;
                if ($userd->save())
                    return response()->json(array('user' => $user, 'token' => $token), 200);
            }
        } catch (Exception $e) {
            return response()->json(array('errors' => [0 => 'Please try agian']), 400);
        }
    }

    public function signUpStore(Request $request)
    {
        $rules = [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'device_id'             => 'required|unique:users',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()), 400);
        try {
            $lastCreatedUser = User::take(1)->orderBy('id', 'desc')->first();
            $user = new User;
            $user->roleId = 3;
            $user->name = $request->fullName;
            $user->email = $request->email;
            $user->mobileNumber = $request->mobileNumber;
            $user->password = md5($request->password);
            $user->status = 1;
            $user->userCreatorId = 1;
            $user->created_at = Carbon::now();

            if (!!$user->save()) {
                $token = $this->tokenGen($user->id);
                $userd = new UserDetail;
                $userd->userId = $user->id;

                if ($request->has('photo')) $userd->photo = $this->uploadImage($request->file('photo'), 'user/photo');

                $userd->address = $request->address;
                if ($userd->save())
                    return response()->json(array('user' => $user, 'token' => $token), 200);
            }
        } catch (Exception $e) {
            return response()->json(array('errors' => [0 => 'Please try agian']), 400);
        }
    }


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where(['email' => $request->email, 'status' => 1])->first();
        !!$user && request()->session()->put(['user' => encryptor('encrypt', $user->id)]);

        if (!!$user) return redirect(route('resetPasswordForm'));
        else return redirect(route('forgotPasswordForm'))->with($this->responseMessage(false, "error", "This email: $request->email not found"));
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::find(encryptor('decrypt', $request->session()->get('user')))->first();
        $user->password = md5($request->password);
        if ($user->save()) return redirect(route('signInForm'))->with($this->responseMessage(true, null, "Password reset successfully. Now you can login"));
    }

    public function signOut()
    {
        //$url = $request->input('url');
        //request()->session()->flush();
        request()->session()->forget(['user', 'email', 'name', 'username', 'mobileNumber', 'roleId', 'uphoto']);
        return redirect(route('signInForm'))->with($this->responseMessage(true, "error", 'Successfully logout.'));
    }


    public function tokenGen($id)
    {
        $ts = User::findOrFail($id);
        /* check if already has tocken or not */
        if ($ts->api_token) {
            $ts->save();
            return $ts->api_token;
        } else {
            $token = Str::random(8) . $id . Str::random(10);
            $ts->api_token = $token;
            $ts->save();
            return $token;
        }
    }
}
