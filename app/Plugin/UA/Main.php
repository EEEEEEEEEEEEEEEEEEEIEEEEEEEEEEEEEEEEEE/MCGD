<?php
namespace App\Plugin\UA;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Provider\WhichBrowser;
use Request;

class Main
{
    public $text=[];
    public $img=[];
    public function run($input){
		$provider = new WhichBrowser();
		$result = $provider->parse($_SERVER['HTTP_USER_AGENT']);
		$city = new \ipip\db\City(__dir__ .'\ipipfree.ipdb');
        $this->text=[
            'browser'=>$result->getBrowser()->getName(),
			'os'=>$result->getOperatingSystem()->getName(),
			'device'=>$result->getDevice()->getBrand(),
			'city'=>$city->find(Request::getClientIp(), 'CN')[2]
        ];
    }
}
