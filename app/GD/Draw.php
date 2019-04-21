<?php
namespace App\GD;
use App\Base;
class Draw
{
    private $main;
    public $replace=[];
    public $draw=[
        'width'=>'100',
        'height'=>'100',
        'background'=>'white.jpg',
        'plugin'=>[
            [
                'plugin'=>'Mcstatus',
                'name'=>'T',
                'input'=>[
                    'host'=>'mc.hypixel.net',
                    'port'=>25565
                ]
            ],[
                'plugin'=>'Time',
                'name'=>'Time',
                'input'=>[
                    'type'=>'h:i:sa'
                ]
            ]
        ],
        'draw'=>[
            [
                'text'=>'<?T->motd?>'."\n".'<?Time->time?>',
                'size'=>12,
                'x'=>0,
                'y'=>0,
                'color'=>'#000000',
                'font'=>'msyh.ttf',
                'angle'=>0
            ]
        ]
    ];
    public function main($str){
        $data=json_decode($str);
        $this->draw=$this->read($data);
        $this->plugin();
        $this->main=imagecreatefromstring(file_get_contents(Base::res('image/'.$this->draw['background'])));
        foreach($this->draw['draw'] as $draw){
            $this->draw_color($this->replace($draw['text']),$draw['x'],$draw['y'],$draw['size'],Base::res('font/msyh.ttf'),$draw['angle']);
        }
        $color=imagecolorallocate($this->main,255,0,0);
        imagefttext($this->main, 12, 0, 50, 50, $color, Base::res('font/'.$draw['font']), time());
        imagepng($this->main,'C:\\Users\\Administrator\\PhpstormProjects\\MCGD\\public\\t.png');
        return'<img src="t.png">';
    }
    private function read(&$object){
        if (is_object($object)) {
            $arr = (array)($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach($arr as $varName => $varValue){
                $arr[$varName] = $this->read($varValue);
            }
        }
        return $arr;
    }
    private function plugin(){
        foreach($this->draw['plugin'] as $plugin){
            $plugin_class='\\App\\Plugin\\'.$plugin['plugin'].'\\Main';
            $p=new $plugin_class();
            foreach ($plugin['input'] as $key=>$value){
                $plugin['input'][$key]=$this->input($value);
            }
            $replace_all=$p->run($plugin['input']);
            foreach($replace_all as $key => $value){
                if(!is_null(@$plugin['name'])){
                    $key=$plugin['name'].'->'.$key;
                }
                $this->replace[$key]=$value;
            }
        }
    }
    private function input(string $str){
        foreach($_GET as $key=>$value){
            $str=str_ireplace('<!'.$key.'!>',$value,$str);
        }
        return $str;
    }
    private function replace(string $str){
        foreach($this->replace as $key=>$value){
            $str=str_ireplace('<?'.$key.'?>',$value,$str);
        }
        return $str;
    }
    private function draw_color(string $color_raw, int $x=12, int $y=12, int $size=12, string $font, int $angle=0){
        $lines=explode("\n",$color_raw);
        foreach($lines as $line){
            $raws=explode('§',$line);
            $color=imagecolorallocate($this->main,255,255,255);
            $nx=$x;
            $ny=$y;
            foreach ($raws as $raw) {
                if(@Base::color_code(substr($raw,0,1))){
                    $color_code=Base::color_code(substr($raw,0,1));
                    $color=imagecolorallocate($this->main,
                        Base::code_color($color_code)[0],
                        Base::code_color($color_code)[1],
                        Base::code_color($color_code)[2]);
                    $text=substr($raw,1);
                }else{
                    $text=$raw;
                }
                $box=imagettfbbox($size, $angle, $font, $text);
                imagefttext($this->main, $size, $angle, $nx, $ny-$box[7]+2, $color, $font, $text);
                $nx += $box[2];
                $ny += $box[3]-$box[1];
            }
            $y+=$size+4;
        }
    }
}
