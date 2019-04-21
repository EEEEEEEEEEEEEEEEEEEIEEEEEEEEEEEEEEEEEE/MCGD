<?php

namespace App\Http\Controllers;
use App\Servers;
use App\GD\Draw;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function draw($id){
        $server=Servers::findOrFail($id);
        $controller=new Draw();
        return $controller->main($server->data);
    }
}
