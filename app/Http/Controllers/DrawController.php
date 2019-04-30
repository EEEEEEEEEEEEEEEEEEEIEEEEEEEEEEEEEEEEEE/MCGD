<?php

namespace App\Http\Controllers;
use App\Servers;
use App\GD\Draw;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function png($id){
        $server=Servers::findOrFail($id);
        $controller=new Draw();
        return response($controller->main($server->data),200)
            ->header('Content-Type', 'image/png');
    }
    public function base64(Request $request){
        $data=$request->input('data');
        $controller=new Draw();
        return response('data:image/png;base64,'.base64_encode($controller->main($data)),200)
            ->header('Content-Type','text/plain');
    }
}
