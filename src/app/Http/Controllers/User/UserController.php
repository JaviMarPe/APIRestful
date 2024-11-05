<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            Log::info("store function request = ".json_encode($request->all()));
     
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:6', 'confirmed'],
            ]);

            if ($validator->fails()) {
                Log::info("Validation fails = ".$validator->errors());
                return $this->errorResponse($validator->errors(), 420);
            }

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verified' => User::USUARIO_NO_VERIFICADO,
                'verification_token' => User::generarVerificationToken(),
                'admin' => User::USUARIO_REGULAR,
            ]);

            Log::info("store function new user = ".json_encode($newUser));
    
            return $this->successResponse($newUser, 201);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 423);
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the user.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Log::info('User retrieved', ['user' => $user]);
        return $this->showOne($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['email', 'unique:users'],
                'password' => ['min:6', 'confirmed'],
                'admin' => 'in:'.User::USUARIO_ADMIN.','.User::USUARIO_REGULAR,
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 420);
            }

            if($request->has('name')){
                $user->name = $request->name;
            }

            if($request->has('email') && $user->email != $request->email){
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verification_token = User::generarVerificationToken();
                $user->email = $request->email;
            }

            if($request->has('password')){
                $user->password = Hash::make($request->password);
            }

            if($request->has('admin')){
                if (!$user->esVerificado()) {
                    return response()->json(['error' => 'Usuarios verificados solo pueden cambiar su valor de admin'], 409);
                }
                $user->admin = $request->admin;
            }

            if(!$user->isDirty()){
                return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar'], 422);
            } 

            $user->save();
    
            return $this->successResponse($user, 201);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->error_log, 423);
        } catch (ValidationException $e) {
            //return $this->errorResponse($e->errors(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the user.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse($user);
    }

    public function verify($token)
    {
        try {
            $user = User::where('verification_token', $token)->firstOrFail();

            Log::info("Verify function user object ".json_encode($user));
            if($user->isEmpty){
                return $this->errorResponse('There is no user with this token');
            }
    
            $user->verified = User::USUARIO_VERIFICADO;
            $user->verification_token = null;
            $user->save();
    
            return $this->successResponse('Your account has been verified');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 415);
        }
    }

    public function resend(User $user)
    {
        try {
            if ($user->esVerificado()) {
                return $this->errorResponse('This user is already verified', 409);
            }
            
            retry(5, function() use ($user){
                Mail::to($user)->send(new UserCreated($user));
            }, 100);

            return $this->successResponse('Verfied mail is already resend', 200);
            

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
