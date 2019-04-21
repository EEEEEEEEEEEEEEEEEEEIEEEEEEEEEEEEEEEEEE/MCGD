<?php
namespace Plugin\Mcstatus;
use Base;
use MinecraftServerStatus\MinecraftServerStatus;
class Main
{
    public function run($input){
        $response = MinecraftServerStatus::query($input['host'], $input['port']);
        //var_dump($response);
        if(@$response['description_raw']->extra){
            $motd=$this::motd_extra($response['description_raw']->extra);
        }elseif(@$response['description_raw']->translate){
            $motd=$this->motd_text($response['description_raw']->translate);
        }elseif(@$response['description_raw']->text){
            $motd=$this->motd_text($response['description_raw']->text);
        }elseif(@$response['description_raw']){
            $motd=$this->motd_text($response['description_raw']);
        }else{
            $motd='Wrong';
        }
        return [
            'players'=>$response['players'],
            'max_players'=>$response['max_players'],
            'ping'=>$response['ping'],
            'version'=>$response['version'],
            'motd'=>$motd,
            'protocol'=>$response['protocol'],
            'mod_count'=>@count(@$response['modinfo']->modList),
            'mod_type'=>@$response['modinfo']->type,
        ];
    }
    private function motd_extra($extra){
        $motd='';
        foreach ($extra as $raw) {
            $motd.=Base::$color_code[$raw->color].$raw->text;
        }
        return $motd;
    }
    private function motd_text($text){
        $text = str_replace('§l', '', $text); //加粗
        $text = str_replace('§m', '', $text); //中划
        $text = str_replace('§n', '', $text); //下划
        $text = str_replace('§o', '', $text); //斜体
        $text = str_replace('§k', '', $text); //随机
        return $text;
    }
}