<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /** @var object $user */
    public $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function($request, $next) {
            $this->user = (object) session('user');
            $this->user->created_at = Carbon::parse($this->user->created_at);
            $this->user->updated_at = Carbon::parse($this->user->updated_at);

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->user;
        return view('home', compact('user'));
    }

    public function home()
    {
        return redirect(route('index'));
    }
}
