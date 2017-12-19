<?php

$useDB = true;

require( '../head.php' );

use Util\CommonUtil as CU;
use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;
use Control\UserController as US;
use Config\Config;

$basename = Config::basename;

US::startSession();

$openid = SU::getval( 'openid' );
if( !$openid )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/wechatcontrolpanel.php';
    $oauth->redirect()->send();
    die( 0 );
}

VU::head( '微信控制面板' );
?>
    <br />
    <div id="frm">
        <div class="form-group">
            <input type="button" class="btn btn-primary form-control" id="getMenus" value="获取菜单">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-primary form-control" id="setMenus" value="设置菜单">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-primary form-control" id="removeMenus" value="删除菜单">
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-primary form-control" id="addNewsReply" value="添加回复">
        </div>
    </div>

    <script>
    function jump(url)
    {
        window.location.href = url;
    }

    $('#getMenus').on('click', function(){
        jump('/<?=$basename?>/api/Wechat/getMenus');
    });

    $('#setMenus').on('click', function(){
        callAPI({
            url : '/<?=Config::basename?>/api/Wechat/setMenus',
            success : function(data){
                showModal('设置成功');
            }
        });
    });

    $('#removeMenus').on('click', function(){
        callAPI({
            url : '/<?=Config::basename?>/api/Wechat/removeMenus',
            success : function(data){
                showModal('删除成功');
            }
        });
    });

    $('#addNewsReply').on('click', function(){
        callAPI({
            url : '/<?=Config::basename?>/api/Wechat/addNewsReply',
            success : function(data){
                showModal('添加成功');
            }
        });
    });
    </script>

<?php
VU::foot();