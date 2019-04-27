<?php

namespace App\Http\Controllers;

use Grpc\Server;
use Illuminate\Http\Request;
use App\Servers;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::check()) {
            $server = Servers::where('user_id', '=', Auth::id())->first();
            if (!$server) {
                $server = new Servers();
                $server->user_id = Auth::id();
                $server->data = '';
                $server->saveOrFail();
            }
            return view('home')->with([
                'server_data' => $server->data
            ]);
        }else{
          abort(404);
        }
    }
}
