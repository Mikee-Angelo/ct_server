<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class SetupController extends Controller
{
    public function initialize() { 
        $isAdminExist = User::with('roles')->exists();

        if(!$isAdminExist) return redirect()->route('register');

        return redirect()->route('login');  
    }
}
