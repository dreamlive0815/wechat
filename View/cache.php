<?php

$useDB = true;

require( '../head.php' );

use Util\CommonUtil as CU;
use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;
use Control\UserController as US;
use Config\Config;

US::startSession();

$openid = SU::getval( 'openid' );
if( !$openid )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/cache.php';
    $oauth->redirect()->send();
    die( 0 );
}

$type = CU::getR( 'type' );
VU::head( "缓存[{$type}]" );
?>
    <textarea id="txt" class="form-control" style="width: 100%;" rows="40">
    </textarea>

    <script>
    callAPI({
        url : '/<?=Config::basename?>/api/User/getCache',
        data : {
            type : '<?=$type?>'
        },
        success : function(data){
            $('#txt').val(data);
        }
    });
    </script>
<?php
VU::foot();