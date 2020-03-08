<?php

require 'TilonLib/functions.php';
require 'TilonLib/Settings.php';
require 'TilonLib/Tilon.php';

use Tilon\TilonFrame;
use Tilon\Settings;

$bot = new TilonFrame(Settings::token);
$bot->setWebhook("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
$update = $bot->getUpDate();

if(isset($update->message)){
$type = $update->message->chat->type;
$chat_id = $update->message->chat->id;
$msg_id = $update->message->message_id;
$audio = $update->message->audio;
$title = $audio->title;
$performer = $audio->performer;
$duration = $audio->duration;
$size = $audio->file_size;

    if(isset($update->message->text)){
        $text = $update->message->text;
        $ltext = mb_strtolower($text);
        if(startCommand('start', $ltext.' ') !== false){
            $bot->Tsend($chat_id, "Salom, Bot ishlamoqda.");
        }
    }
    if ($audio) {
      $bot->Tsend($chat_id,"siz audio tashladingiz.");
    }
}