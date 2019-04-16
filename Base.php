<?php

class Base
{
    public static $color_code= [
        '0' => 'black',
        'black' => '0',
        '1' => 'dark_blue',
        'dark_blue' => '1',
        '2' => 'dark_green',
        'dark_green' => '2',
        '3' => 'dark_aqua',
        'dark_aqua' => '3',
        '4' => 'dark_red',
        'dark_red' => '4',
        '5' => 'dark_purple',
        'dark_purple' => '5',
        '6' => 'gold',
        'gold' => '6',
        '7' => 'gray',
        'gray' => '7',
        '8' => 'dark_gray',
        'dark_gray' => '8',
        '9' => 'blue',
        'blue' => '9',
        'a' => 'green',
        'green' => 'a',
        'b' => 'aqua',
        'aqua' => 'b',
        'c' => 'red',
        'red' => 'c',
        'd' => 'light_purple',
        'light_purple' => 'd',
        'e' => 'yellow',
        'yellow' => 'e',
        'f' => 'white',
        'white' => 'f',
        'r' => 'white'
    ];
    public static function res($path){
        return __dir__.'/res/'.$path;
    }
}