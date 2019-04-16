<?php
namespace GD;
use Base;
use Grafika\Grafika;
use Grafika\Color;
class Draw
{
    private $editor;
    private $main;
    public $replace=[];
    public $draw=[
        'width'=>100,
        'height'=>100,
        'background'=>'white.jpg',
        'plugin'=>[
            [
                'plugin'=>'Mcstatus',
                'name'=>'T',
                'input'=>[
                    'host'=>'play.mcyc.win',
                    'port'=>25565
                ]
            ].[
                'plugin'=>'Time',
                'name'=>'Timne',
                'input'=>[
                    'type'=>'play.mcyc.win'
                ]
            ]
        ],
        'draw'=>[
            [
                'text'=>'<?T->motd?>'."\n".'<?Time->time?>',
                'size'=>28,
                'x'=>0,
                'y'=>0,
                'color'=>'#000000',
                'font'=>'msyh.ttf',
                'angle'=>0,
            ]
        ]
    ];
    public function main(){
        $this->plugin();
        $this->editor = Grafika::createEditor();
        $this->editor->open($this->main, Base::res('image/'.$this->draw['background']));
        foreach($this->draw['draw'] as $draw){
            $this->editor->text($this->main, $this->replace($draw['text']), $draw['size'], $draw['x'], $draw['y'],
                new Color($draw['color']), Base::res('font/'.$draw['font']),$draw['angle'] );
        }
        $this->editor->save($this->main,'t.jpg','jpg');
    }
    private function plugin(){
        foreach($this->draw['plugin'] as $plugin){
            $plugin_class='\\Plugin\\'.$plugin['plugin'].'\\Main';
            $p=new $plugin_class();
            $replace_all=$p->run($plugin['input']);
            foreach($replace_all as $key => $value){
                if(!is_null(@$plugin['name'])){
                    $key=$plugin['name'].'->'.$key;
                }
                $this->replace[$key]=$value;
            }
        }
    }
    private function replace($str){
        foreach($this->replace as $key=>$value){
            $str=str_ireplace('<?'.$key.'?>',$value,$str);
        }
        return $str;
    }
}