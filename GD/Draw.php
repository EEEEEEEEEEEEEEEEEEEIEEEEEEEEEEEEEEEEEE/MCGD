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
        'width'=>'100',
        'height'=>'100',
        'background'=>'white.jpg',
        'plugin'=>[
            [
                'plugin'=>'Mcstatus',
                'name'=>'T',
                'input'=>[
                    'host'=>'play.mcyc.win',
                    'port'=>25565
                ]
            ],[
                'plugin'=>'Time',
                'name'=>'Time',
                'input'=>[
                    'type'=>'play.mcyc.win'
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
                'angle'=>330
            ]
        ]
    ];
    public function main(){
        $this->plugin();
        $this->main=imagecreatefromstring(file_get_contents(Base::res('image/'.$this->draw['background'])));
        foreach($this->draw['draw'] as $draw){
            $this->draw_color($this->replace($draw['text']),$draw['x'],$draw['y'],$draw['size'],Base::res('font/msyh.ttf'),$draw['angle']);
        }
        $color=imagecolorallocate($this->main,255,0,0);
        imagefttext($this->main, 12, 0, 50, 50, $color, Base::res('font/'.$draw['font']), time());
        imagepng($this->main,'C:\Users\Administrator\PhpstormProjects\MCGD\t.png');
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

    private function draw_color($color_raw, $x=12, $y=12, $size=12, $font, $angle=0,$fy=false){
        $lines=explode("\n",$color_raw);
        //var_dump($lines);
        $ny=$y;
        if(!$fy){
            $fy=$size;
        }

        foreach($lines as $line){
            $raws=explode('§',$line);
            //var_dump($raws);
            $color=imagecolorallocate($this->main,255,255,255);
            $nx=$x;
            $ny=$y;
            $sety=true;
            foreach ($raws as $raw) {
                if(@Base::color_code(substr($raw,0,1))){
                    $color_code=Base::color_code(substr($raw,0,1));
                    $color=imagecolorallocate($this->main,
                        Base::code_color($color_code)[0],
                        Base::code_color($color_code)[1],
                        Base::code_color($color_code)[2]);
                    //var_dump(Base::code_color($color_code));
                    $text=substr($raw,1);
                }else{
                    $text=$raw;
                }
                echo $text;
                var_dump($ny);
                $box=imagettfbbox($size, $angle, $font, $text);
                imagefttext($this->main, $size, $angle, $nx, $ny, $color, $font, $text);
                $nx += $box[2];
                $ny += $box[3]-$box[1];
                var_dump(imagettfbbox($size, $angle, $font, $text));
                if($sety&&$text!=''){
                    $y+=$box[1]-$box[7];
                    $sety=false;
                }
            }
        }
    }
}