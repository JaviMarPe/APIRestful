<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CategoryController extends ApiController
{
    public function __construct() 
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('client.credential')->only(['index', 'show']);
        $this->middleware('transform.input'.CategoryTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return $this->showAll($category, 200);
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
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 420);
            }

            $newCategory = Category::create($request->all());
    
            return $this->successResponse($newCategory, 201);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->error_log, 423);
        } catch (ValidationException $e) {
            //return $this->errorResponse($e->error_log(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the Category.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->successResponse($category, 200);
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
    public function update(Request $request, Category $category)
    {
        try {

            $category->fill($request->only([
                'name',
                'description'
            ]));

            if(!$category->isDirty()){
                return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar'], 422);
            } 

            $category->save();
    
            return $this->successResponse($category, 201);

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
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->successResponse($category, 201);
    }
}
