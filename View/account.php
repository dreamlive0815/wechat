<?php

$useDB = true;

require( '../head.php' );

use Util\CommonUtil as CU;
use Util\ViewUtil as VU;
use Util\Session\SessionUtil as SU;
use Control\UserController as US;

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

$filter = CU::getR( 'filter' );
if( $filter )
{
    $f_a = explode( ',', $filter );
    $real_fields = [];
    foreach( $fields as $k => $v )
    {
        if( $k == 'sid' || array_search( $k, $f_a ) !== false ) $real_fields[$k] = $v;
    }
}
else $real_fields = $fields;

$fs = array_map( function( $v ) { return "'{$v}'"; },  array_keys( $real_fields ) );
$fs_str = '[' . implode( ',', $fs ) . ']';

VU::head( '账号信息设置页面' );
?>
    <form id="frm">
        <br />
<?php
foreach( $real_fields as $k => $v )
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
    function beforeSubmit()
    {
        if(!$('#nic_passwd').val()) $('#nic_passwd').val('123456');
        if(!$('#lib_passwd').val()) $('#lib_passwd').val('0000');
        if(!$('#edu_passwd').val()) $('#edu_passwd').val($('#idcard').val());
        if(!$('#ecard_passwd').val()) $('#ecard_passwd').val($('#idcard').val());
    }

    callAPI({
        url : '/wechat/api/User/getUserInfo',
        success : function(data){
            var fs = <?=$fs_str?>;
            for(var i=0;i<fs.length;++i)
            {
                var key = fs[i];
                $('#' + key).val(data[key]);
            }
        },
        fail : function(){}
    });

    $('#frm').submit(function(event){
        beforeSubmit();
        event.preventDefault();

        callAPI({
            url : '/wechat/api/User/updateUserInfo',
            data : $('#frm').serialize(),
            success : function(data){
                showModal('设置成功');
            },
            fail : function(code,msg){
                showModal(msg);
            }
        });
        
        return false;
    });
    </script>
<?php
VU::foot();