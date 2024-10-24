<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return $this->successResponse($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:6', 'confirmed'],
            ]);

            if ($validator->fails()) {
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
    
            return $this->successResponse($newUser, 201);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->error_log, 423);
        } catch (ValidationException $e) {
            //return $this->errorResponse($e->error_log(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the user.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return $this->successResponse($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            
            $user = User::findOrFail($id);

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
    public function destroy(string $id)
    {
        //$user = User::destroy($id);

        $user = User::findOrFail($id);

        //$user->products()->delete();

        $user->delete();

        return response()->json(['data' => $user], 201);
    }
}
