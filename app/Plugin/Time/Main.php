<?php
namespace App\Plugin\Time;
class Main
{
    public $text=[];
    public $img=[];
    public function run($input){
        $this->text=[
            'time'=>date($input['type'])
        ];
    }
}
