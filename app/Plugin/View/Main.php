<?php
namespace App\Plugin\View;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Provider\WhichBrowser;
use Request;

class Main
{
    public $text=[];
    public $img=[];
	public $server;
	private $view;
	public function __construct($server){
		$this->server=$server;
		$sd=json_decode($server->data);
		if(@$sd->view){
			$sd->view++;
		}else{
			$sd->view=1;
		}
		$server->data=json_encode($sd);
		$server->save();
		$this->view=$sd->view;
	}
    public function run($input){
        $this->text=[
            'view'=>$this->view
        ];
    }
}
