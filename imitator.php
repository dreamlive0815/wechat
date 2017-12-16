<?php

require( './head.php' );

use Util\CurlUtil;
use Util\CommonUtil as CU;

$url = 'http://localhost/wechat/index.php';

$text = CU::getR( 'text' );
if( !$text ) $text = 'COURSE NOCACHE   year=2015-2016  semester=2';

$message = "<xml>
<ToUserName><![CDATA[gh_3e5937f3358d]]></ToUserName>
<FromUserName><![CDATA[oQ4KVw14cKQ4lucVr4N8mJNY_Cro]]></FromUserName>
<CreateTime>1467085572</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[Exam]]></EventKey>
<Content>{$text}</Content>
<MsgId>6301084552610610382</MsgId>
</xml>
";
//oQ4KVw14cKQ4lucVr4N8mJNY_Cro 个人号
//oZy0Mw58RMSmyys0WR9xRp-y5v0U 测试号

$curl = new CurlUtil( $url );
$response = $curl->timeout( 10000 )->POST( $message );
echo $response;