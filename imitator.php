<?php

require( './head.php' );

use Util\CurlUtil;
use Util\CommonUtil as CU;

$url = 'http://localhost/wechat/index.php';

$text = CU::getR( 'text' );
if( !$text ) $text = '分享3L的单曲《月齢11.3のキャンドルマジック (Getsurei 11.3 no Candle Magic)》: http://music.163.com/song/22820952/?userid=352148975 (来自@网易云音乐) download';

$message = "<xml>
<ToUserName><![CDATA[gh_3e5937f3358d]]></ToUserName>
<FromUserName><![CDATA[oQ4KVw14cKQ4lucVr4N8mJNY_Cro]]></FromUserName>
<CreateTime>1467085572</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[Exam]]></EventKey>
<Content><![CDATA[{$text}]]></Content>
<MsgId>6301084552610610382</MsgId>
</xml>
";
//oQ4KVw14cKQ4lucVr4N8mJNY_Cro 个人号
//oZy0Mw58RMSmyys0WR9xRp-y5v0U 测试号

$curl = new CurlUtil( $url );
$response = $curl->timeout( 10000 )->POST( $message );
echo $response;