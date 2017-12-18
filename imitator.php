<?php

require( './head.php' );

use Util\CurlUtil;
use Util\CommonUtil as CU;

$url = 'http://localhost/wechat/index.php';

$text = CU::getR( 'text' );
if( !$text ) $text = 'http://kns.cnki.net/KCMS/detail/detail.aspx?dbcode=CMFD&dbname=CMFD201601&filename=1015646736.nh&uid=WEEvREcwSlJHSldRa1FhdXNXYXFuU20zYnFKeERTZGhaV2tWdkZoa2ZXUT0=$9A4hF_YAuvQ5obgVAqNKPCYcEjKensW4ggI8Fm4gTkoUKaID8j8gFw!!&v=MzE5MjhHTmJQcVpFYlBJUjhlWDFMdXhZUzdEaDFUM3FUcldNMUZyQ1VSTDJlWmVkb0Z5dmhVTC9QVkYyNkc3Vzg=';

$message = "<xml>
<ToUserName><![CDATA[gh_3e5937f3358d]]></ToUserName>
<FromUserName><![CDATA[oQ4KVw14cKQ4lucVr4N8mJNY_Cro]]></FromUserName>
<CreateTime>1467085572</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[Course_today]]></EventKey>
<Content><![CDATA[{$text}]]></Content>
<MsgId>6301084552610610382</MsgId>
</xml>
";
//oQ4KVw14cKQ4lucVr4N8mJNY_Cro 个人号
//oZy0Mw58RMSmyys0WR9xRp-y5v0U 测试号

$curl = new CurlUtil( $url );
$response = $curl->timeout( 10000 )->POST( $message );
echo $response;