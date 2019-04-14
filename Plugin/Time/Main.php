<?php
namespace Plugin\Time;
class Main
{
    public function run($input){
        return [
            'time'=>date($input['type'])
        ];
    }
}