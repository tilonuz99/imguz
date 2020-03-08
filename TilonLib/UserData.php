<?php

namespace Tilon;
class UserData
{
    private $user_id;
    private $filename;
    private $edited = false;
    private $event = false;
    private $data = [];
    private $lang = 'uz';
    private $settings = [];

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->filename = ROOT."/data/users/".$this->user_id;
        if(!file_exists($this->filename)) return;
        $content = json_decode(file_get_contents($this->filename), true);
        $this->event = isset($content['event']) ? $content['event'] : false;
        $this->data = isset($content['data']) ? $content['data'] : [];
        $this->lang = isset($content['lang']) ? $content['lang'] : 'uz';
        $this->settings = isset($content['settings']) ? $content['settings'] : [];
    }
    public function getEvent()
    {
        return $this->event;
    }
    public function setEvent($event){
        $this->event = $event;
        $this->edited = true;
        return $this;
    }
    public function clearEvent(){
        $this->event = false;
        $this->data = [];
        $this->edited = true;
        return $this;
    }
    public function getData()
    {
        return $this->data;
    }
    public function setData($data){
        $this->data = $data;
        $this->edited = true;
        return $this;
    }
    public function addData($key, $data)
    {
        $this->data[$key] = $data;
        $this->edited = true;
        return $this;
    }
    public function getLang()
    {
        return $this->lang;
    }
    public function setLang($lang)
    {
        $this->edited = true;
        $this->lang = $lang;
        return $this;
    }
    public function getSettings()
    {
        return $this->settings;
    }
    public function addSetting($key, $value)
    {
        $this->settings[$key] = $value;
        return $this;
    }
    public function close()
    {
        if($this->edited){
            file_put_contents($this->filename, json_encode(['event'=>$this->event, 'data'=>$this->data, 'lang'=>$this->lang]));
        }
    }

}