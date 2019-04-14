<?php
namespace Plugin;
class Time
{
    public function run($input){
        return [
            'time'=>date($input['type'])
        ];
    }
}