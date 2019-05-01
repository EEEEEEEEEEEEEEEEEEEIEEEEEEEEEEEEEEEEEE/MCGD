<?php
namespace App\GD;
use App\Base;
class Draw
{
    private $main;
    public $replace=[];
    public $img=[];
    public $draw=[];
    public function main($str){
        $data=json_decode($str);
        $this->draw=$this->read($data);
        $this->plugin();
        $bg=imagecreatefromstring(file_get_contents(Base::res('image/'.$this->draw['background'])));
        $this->main= imagecreatetruecolor($this->draw['width'],$this->draw['height']);
        imagecopyresized($this->main,$bg,0,0,0,0,$this->draw['width'],$this->draw['height'],imagesx($bg),imagesy($bg));
        foreach($this->draw['img'] as $img){
            $src=imagecreatefromstring($this->img[$img['data']]);
            imagecopyresized($this->main,$src,$img['x'],$img['y'],0,0,$img['width'],$img['height'],imagesx($src),imagesy($src));
        }
        foreach($this->draw['draw'] as $draw){
            $this->draw_color($this->replace($draw['text']),$draw['x'],$draw['y'],$draw['size'],Base::res('font/msyh.ttf'),$draw['angle']);
        }
        ob_start();
        imagepng($this->main);
        $op=ob_get_contents();
        ob_end_clean();
        return $op;
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
            $p->run($plugin['input']);
            foreach($p->text as $key => $value){
                if(!is_null(@$plugin['name'])){
                    $key=$plugin['name'].'->'.$key;
                }
                $this->replace[$key]=$value;
            }
            foreach($p->img as $key => $value){
                if(!is_null(@$plugin['name'])){
                    $key=$plugin['name'].'->'.$key;
                }
                $this->img[$key]=$value;
            }
        }
    }
    private function input(string $str){
        foreach($_GET as $key=>$value){
            $str=str_ireplace('<!'.$key.'!>',$value,$str);
        }
        return $str;
    }
    private function replace(string $str,array $had=[]){
        foreach($this->replace as $key=>$value){
            if(@$had[$key]){
                continue;
            }else{
                if(strpos($str,'<?'.$key.'?>')===false){
                    continue;
                }else{
                    $arr=$had;
                    $arr[$key]=true;
                    $str=str_ireplace('<?'.$key.'?>',$this->replace($value,$arr),$str);
                }
            }
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
            $isf=true;
            foreach ($raws as $raw) {
                if(@Base::color_code(substr($raw,0,1)) && !$isf){
                    $color_code=Base::color_code(substr($raw,0,1));
                    $color=imagecolorallocate($this->main,
                        Base::code_color($color_code)[0],
                        Base::code_color($color_code)[1],
                        Base::code_color($color_code)[2]);
                    $text=substr($raw,1);
                }else{
                    $text=$raw;
                }
                $isf=false;
                $box=imagettfbbox($size, $angle, $font, $text);
                imagefttext($this->main, $size, $angle, $nx, $ny-$box[7]+2, $color, $font, $text);
                $nx += $box[2];
                $ny += $box[3]-$box[1];
            }
            $y+=$size+4;
        }
    }
}
