<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
{
    if (!$request->expectsJson()) {
        // Gunakan flash untuk pesan yang hanya ditampilkan satu kali
        session()->flash('error', 'Silakan login terlebih dahulu!');
        return route('login');
    }
}

}
