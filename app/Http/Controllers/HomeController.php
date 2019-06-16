<?php

namespace App\Http\Controllers;

use Grpc\Server;
use Illuminate\Http\Request;
use App\Servers;
use App\Base;
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
                'server_data' =>json_encode(json_decode($server->data),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
				'server_id'=>$server->id
            ]);
        }else{
          abort(404);
        }
    }

    public function save(Request $request)
    {
        if(Auth::check()) {
            $server = Servers::saveOrFail($request->input('server'));
 
            $server->data = json_encode(json_decode($request->input('data')));
            $server->saveOrFail();

            return $this->index();
        }else{
            abort(404);
        }
    }
	public function upload(Request $request)
    {
        if(Auth::check()) {
            $file = $request->file('file');
			
			if (@$file->isValid()){
				$realPath = $file->getRealPath();
				$md5=md5(file_get_contents($realPath));
				file_put_contents(Base::res('image/userimg/'.$md5),file_get_contents($realPath));
				return response(json_encode(['md5'=>$md5]),200)
					->header('Content-Type','text/plain');
			}
            
        }else{
            abort(404);
        }
    }
}
