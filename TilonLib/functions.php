<?php

function startWith($word, $sentence, $raz = '_'){
    $pattern = "#^$word{$raz}#";
    if(preg_match($pattern, $sentence)!=0)
        return trim(mb_substr($sentence, mb_strlen($word.$raz)));
    return false;
}
function startCommand($word, $sentence, $raz = ' '){
    return startWith('/'.$word, $sentence, $raz);
}
function clearHtml($text){
    return str_replace(['<','>'],['&lt','&gt'],$text);
}

function str2btn($str){
    $keyboard = new Tilon\Keyboard();
    foreach(explode("\n", $str) as $s){
        $a = array_map('trim', explode('-', $s, 2));
        $keyboard->url([$a[1]=>$a[0]]);
    }
    return $keyboard->getInlineKeyboard();
}
function sendOk(){
    ignore_user_abort(true);
    header("Content-Length: 0");
    header("Connection: Close");
    flush();
}
function id2link($dec){
    return base_convert((int)$dec, 10, 36);
}
function link2id($link){
    return (int)base_convert($link, 36, 10);
}













































