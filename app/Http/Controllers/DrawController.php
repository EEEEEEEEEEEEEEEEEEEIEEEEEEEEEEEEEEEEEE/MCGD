<?php

namespace App\Http\Controllers;
use App\Servers;
use App\GD\Draw;
use App\Base;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function png($id){
		if(@session('time')+100<time()){
			session(['time'=>time()]);
			return response(file_get_contents(Base::res('default/main.png')),200)
           ->header('Content-Type','image/png')
		   ->header('refresh','1');
		}
        $server=Servers::findOrFail($id);
        $controller=new Draw($server,$server->data);
		session(['time'=>0]);
        return response($controller->main(),200)
           ->header('Content-Type','image/png')
		   ->header('Cache-Control','Private');
    }
    public function base64(Request $request){
		$data=$request->input('data');
		$server=Servers::findOrFail($request->input('server'));
		$controller=new Draw($server,$data);
		$ret['url']='data:image/png;base64,'.base64_encode($controller->main());
		$ret['replace']=$controller->replace;
		$ret['img']=[];
		foreach ($controller->img as $key=>$img) {
			$ret['img'][$key]='data:image/png;base64,'.base64_encode($img);
		}
        return response(json_encode($ret),200)
            ->header('Content-Type','text/plain');
    }
}
