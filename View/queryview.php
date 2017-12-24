<?php

$useDB = true;

require( '../head.php' );

use Util\CommonUtil as CU;
use Util\ViewUtil as VU;
use Util\JsonUtil as JSON;
use Util\Session\SessionUtil as SU;
use Control\UserController as US;
use Config\Config;
use Handler\Text;
use Model\Cache;

$type = CU::getR( 'type' );

US::startSession();

$openid = SU::getval( 'openid' );
if( !$openid )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/cache.php?type=' . $type;
    $oauth->redirect()->send();
    die( 0 );
}
//$openid = 'oQ4KVw14cKQ4lucVr4N8mJNY_Cro';

$type = CU::getR( 'type' );
$type = ucfirst( strtolower( $type ) );
if( !Text::isQuery( $type ) ) throw new \Exception( '不存在指定类型的缓存' );
$message = new \stdClass(); $message->FromUserName = $openid; Text::$message = $message;
$query = Text::getQuery( Text::renderQueryName( $type ) );
$args = array_merge( [ 'owner' => $openid, 'type' => $query->getType() ], $query->buildArgs() );
$cache = Cache::getCache( $args );
if( !$cache->id ) throw new \Exception( '缓存不存在' );
$json = JSON::parse( $cache->data );
if( !$json ) throw new \Exception( '解析缓存数据时出错' );
$query->fromCache = true; $query->cache = $cache;
$view = $query->renderView( $json['result'] );

VU::head( sprintf( '查询[%s]', CU::getR( 'type' ) ) );
?>
<br />
<?php

foreach( $view as $ul )
{
    $args = $ul['args'];
    $lis = $ul['lis'];?>
    <ul class="list-group">
<?php foreach( $lis as $li ) { ?>
        <li class="list-group-item"><?=$li?></li>
<?php } ?>
    </ul>
<?php
}

VU::foot();