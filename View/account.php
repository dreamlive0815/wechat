<?php

$useDB = true;

require( '../head.php' );

use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;
use Control\UserController as US;

$displayer = function ( $args ) {
    $a = [
        'id' => '', 'label' => '', 'note' => '', 'max' => 32, 'required' => false,
    ];
    $a = array_merge( $a, $args );?>
        <div class="form-group">
            <label class="control-label"><?=$a['label']?></label>
            <input type="text" class="form-control" id="<?=$a['id']?>" name="<?=$a['id']?>" placeholder="<?=$a['note']?>" maxlength="<?=$a['max']?>" <?=$a['required']?'required':''?>>
        </div>
<?php };


$fields = [
    'sid' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'sid', 'label' => '学号', 'note' => '', 'required' => true ] ],
    'idcard' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'idcard', 'label' => '身份证后六位', 'note' => '', 'required' => true ] ],
    'edu_passwd' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'edu_passwd', 'label' => '教务管理系统密码', 'note' => '默认为身份证后六位' ] ],
    'ecard_passwd' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'ecard_passwd', 'label' => '校园卡密码', 'note' => '默认为身份证后六位' ] ],
    'nic_passwd' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'nic_passwd', 'label' => '网络信息中心密码', 'note' => '默认为123456' ] ],
    'lib_passwd' => [ 'displayer' => $displayer, 'args' => [ 'id' => 'lib_passwd', 'label' => '图书管理系统密码', 'note' => '默认为0000' ] ],
];
/*
US::startSession();

$openid = SU::getval( 'openid' );
if( !$openid )
{
    require( '../wechat_head.php' );
    $oauth = $app->oauth;
    
    $_SESSION['target_url'] = 'View/account.php';
    $oauth->redirect()->send();
    die( 0 );
}
//action="/api/User/updateUser" method="POST" 
*/

VU::head( 'test' );
?>
    <form id="frm">
        <br />
<?php
foreach( $fields as $k => $v )
{
    $displayer = $v['displayer'];
    $displayer( $v['args'] );
}
?>
        <div class="form-group">
            <input type="submit" class="btn btn-default form-control" value="提交">
        </div>
    </form>
    <script>
    $('#frm').submit(function(event){
        event.preventDefault();
        callAPI({
            url : '/wechat/api/User/getUserInfo',
            success : function(data){

            }
        })
        return false;
    });
    </script>
<?php
VU::foot();