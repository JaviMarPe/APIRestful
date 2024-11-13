<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    use ApiResponser;

    /* constructor */
    public function __construct(protected User $user) 
    {
       $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function getTokens()
    {
        Log::info('get Token view');
        return view('home.authorize', $this->user);
    }
}

