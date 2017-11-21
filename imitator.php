<?php

require( 'CurlUtil.php' );

use Api\CurlUtil;

$url = 'http://localhost/wechat/index.php';

$message = '<xml>
<ToUserName><![CDATA[gh_3e5937f3358d]]></ToUserName>
<FromUserName><![CDATA[oQ4KVw14cKQ4lucVr4N8mJNY_Cro]]></FromUserName>
<CreateTime>1467085572</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[Exam]]></EventKey>
<Content>##考试</Content>
<MsgId>6301084552610610382</MsgId>
</xml>
';
//oQ4KVw14cKQ4lucVr4N8mJNY_Cro 个人号

$curl = new CurlUtil( $url );
$response = $curl->POST( $message );
echo $response;