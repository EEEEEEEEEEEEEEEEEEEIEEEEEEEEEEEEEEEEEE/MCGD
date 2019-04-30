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
                'server_data' =>json_encode(json_decode($server->data),JSON_PRETTY_PRINT)
            ]);
        }else{
          abort(404);
        }
    }

    public function save(Request $request)
    {
        if(Auth::check()) {
            $server = Servers::where('user_id', '=', Auth::id())->first();
            if(!$server){
                $server=new Server();
            }
            $server->data = $request->input('data');
            $server->saveOrFail();

            return $this->index();
        }else{
            abort(404);
        }
    }
}
