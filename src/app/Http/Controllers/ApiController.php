<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponser;

    /* constructor */
    public function __construct() 
    {
       $this->middleware('auth:api');
    }

    /* Permite a los administradores realizar los controladores que no tienen policies */
    protected function allowedAdminGate()
    {
        if(Gate::denies('admin_action')){
            throw new AuthorizationException('This action is not allow for this user');
        }
    }
}

