<?php
namespace GD;
class Draw
{
    public $replace=[];
    public $draw=[
        'plugin'=>[
            [
                'plugin'=>'Pain',
                'name'=>'T',
                'input'=>[
                    'text'=>'你好，世界。'
                ]
            ]
        ]
    ];
    public function main(){
        $this->plugin();
        var_dump($this->replace);
    }
    private function plugin(){
        foreach($this->draw['plugin'] as $plugin){
            $plugin_class='\\Plugin\\'.$plugin['plugin'];
            $p=new $plugin_class();
            $replace_all=$p->run($plugin['input']);
            foreach($replace_all as $key => $value){
                $this->replace[$key]=$value;
            }
        }
    }
}