<?php
namespace App\Plugin\UA;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Provider\WhichBrowser;
use Request;

class Main
{
    public $text=[];
    public $img=[];
	public $server;
	public function __construct($server){
		$this->server=$server;
	}
    public function run($input){
		$provider = new WhichBrowser();
		$result = $provider->parse($_SERVER['HTTP_USER_AGENT']);
		try{
			$city = new \ipip\db\City(__dir__ .'\ipipfree.ipdb');
			$ci=$city->find(Request::getClientIp(), 'CN');
		}catch(\InvalidArgumentException $e){
			$ci=[];
		}
		$info='';
		foreach($ci as $pc){
			if($ci){
				$info.=','.$pc;
			}
		}
        $this->text=[
            'browser'=>$result->getBrowser()->getName(),
			'os'=>$result->getOperatingSystem()->getName(),
			'device'=>$result->getDevice()->getBrand(),
			'city'=>$info
        ];
    }
}
