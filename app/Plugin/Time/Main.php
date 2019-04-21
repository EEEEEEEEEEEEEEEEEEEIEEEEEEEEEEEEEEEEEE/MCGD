<?php
namespace App\Plugin\Time;
class Main
{
    public function run($input){
        return [
            'time'=>date($input['type'])
        ];
    }
}
