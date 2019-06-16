<?php
namespace App\Plugin\Time;
class Main
{
    public $text=[];
    public $img=[];
	public $server;
	public function __construct($server){
		$this->server=$server;
	}
    public function run($input){
        $this->text=[
            'time'=>date($input['type'])
        ];
    }
}
