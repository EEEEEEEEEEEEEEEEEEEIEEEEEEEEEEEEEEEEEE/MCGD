<?php

namespace App\Http\Controllers;
use App\Servers;
use App\GD\Draw;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function png($id){
        $server=Servers::findOrFail($id);
        $controller=new Draw($server,$server->data);
        return response($controller->main(),200)
           ->header('Content-Type','image/png')
		   ->header('refresh','10');
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
