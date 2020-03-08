<?php

namespace Tilon;

class TilonFrame
{
    
    public $token;
    public $urlBase  = 'https://api.telegram.org/bot';
    public $url;
    protected $handlers = [];
    
    public function __construct($t){
        $this->token = $t;
        $this->url = $this->urlBase . $t . '/';
    }
    public function setWebhook($site){
        file_get_contents($this->url."setwebhook?url=".$site);
    }
    public function CallBot($method, array $params=null){
        $myCurl = curl_init();
        curl_setopt_array($myCurl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->url.$method,
        ));
        if($params){
            option:curl_setopt_array($myCurl, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($params)
            ));
        }
        $data = curl_exec($myCurl);
        curl_close($myCurl);
        return $data;
    }
    

    public function getUpDate(){
        return json_decode(file_get_contents('php://input'));
    }

    public function getMe(){
        return $this->CallBot('getMe');
    }

    /**
     * @param $id
     * @param $text
     * @param array $params
     * @return mixed
     */
     
    public function Tsend($id, $text, $params=[]){
        return $this->CallBot('sendMessage', array_merge($params,[
            'chat_id'=>$id,
            'text' => $text,
        ]));
    }
    public function Asend(){$id, $path, $params=[]){
        return $this->CallBot('sendMessage', array_merge($params,[
            'chat_id'=>$id,
            'audio' => $path,
        ]));
    }
    public function leaveChat($id){
        return $this->CallBot('leaveChat', [
            'chat_id'=>$id
        ]);
    }
    public function getPrivateLink($id){
        return $this->CallBot('exportChatInviteLink', ['chat_id'=>$id]);
    }
    public function getChatMembersCount($id){
        return $this->CallBot('getChatMembersCount', ['chat_id'=>$id]);
    }
    public function getChat($id, $params=[]){
        return $this->CallBot('getChat', array_merge([
            'chat_id'=>$id,
        ],$params));
    }
    public function sendChatAction($id, $action){
        return $this->CallBot('sendChatAction', [
            'chat_id' => $id,
            'action' => $action,
        ]);
    }
    public function deleteMessage($id, $msg_id){
        return $this->CallBot('deleteMessage', [
           'chat_id' => $id,
           'message_id' => $msg_id,
        ]);
    }
    public function editMessage($id, $text, $msg_id, $params = []){
        return $this->CallBot('editMessageText', array_merge([
            'chat_id'=>$id,
            'text' => $text,
            'message_id'=>$msg_id
        ], $params));
    }
    public function answerCallbackQuery($id, $text = '', $show_alert = 'false', $params = []){
        return $this->CallBot('answerCallbackQuery', array_merge([
            'callback_query_id'=>$id,
            'text' => $text,
            'show_alert' => $show_alert,
        ], $params));
    }
    public function Psend($chat_id, $photo, $caption = '', $params = []){
        return $this->CallBot('sendPhoto', array_merge([
            'chat_id'=>$chat_id,
            'photo' => $photo,
            'caption'=>$caption
        ], $params));
    }
    public function editMessageReplyMarkup($chat_id, $msg_id, $reply_markup = "{}", $params = []){
        return $this->CallBot('editMessageReplyMarkup', array_merge([
            'chat_id'=>$chat_id,
            'message_id'=>$msg_id,
            'reply_markup'=>$reply_markup
        ], $params));
    }
    public function answerInlineQuery($inline_query_id, $results, $params = []){
        return $this->CallBot(__FUNCTION__, array_merge(
            compact('inline_query_id', 'results'),
            $params
        ));
    }
}

class Keyboard{
    private $data_cb =[];
    private $data_cl =[];
    public static function removeCl($selective = false){
        return json_encode([
            'remove_keyboard' => true,
            'selective' => $selective,
        ]);
    }
    public function classic(...$args){
        $arr = [];
        $i = 0;
        foreach ($args as $arg){
            foreach ($arg as $ar){
                $arr[$i][]=['text'=>$ar.''];
            }
            $i++;
        }
        $this->data_cl = array_merge($this->data_cl, $arr);
        return $this;
    }

    public function url($array){
        $this->data_cb[] = $this->replaceKeys($array);
        return $this;
    }
    public function switchInline($array){
        $arr = [];
        foreach ($array as $k=>$a){
            $arr[] = ['text'=>''.$a, 'switch_inline_query' =>''.$k];
        }
        $this->data_cb[] = $arr;
    }
    public function callback($array){
        $this->data_cb[] = $this->replaceKeys($array, false);
        return $this;
    }
    public function getInlineKeyboard($returnJSON = true){
        return $returnJSON ? json_encode(['inline_keyboard'=>$this->data_cb]) : ['inline_keyboard'=>$this->data_cb];
    }
    public function getClassicKeyboard($params = []){
        return json_encode(array_merge(['keyboard'=>$this->data_cl, 'resize_keyboard'=>true], $params));
    }

    private function replaceKeys($array, $is=true){
        $arr = [];
        foreach ($array as $k=>$a){
            $arr[] = ['text'=>''.$a, $is ? 'url' : 'callback_data' =>''.$k];
        }
        return $arr;
    }

}