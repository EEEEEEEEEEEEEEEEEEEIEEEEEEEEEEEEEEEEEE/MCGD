<?php
namespace Plugin;
class Pain
{
    public function run($input){
        return [
            'text'=>$input['text']
        ];
    }
}