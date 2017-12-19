<?php

namespace Handler\Query;

class Net extends Query
{
    static $lifeTime = 3600 * 24;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->nic_passwd;
        $args['fees'] = 1;
        return $args;
    }

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "%s(%s)", $data['sid'], $data['name'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $balance = 0;
        if( $data['fees'] ) $balance = $data['fees'][0]['balance'];
        $s = sprintf( '账户余额: %s元', $balance );
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        $s = sprintf( "寝室楼: %s\n房间: %s\n端口号: %s\nIP地址: %s\n运营商: %s\nDNS1: %s\nDNS2: %s", $data['house'], $data['room'], $data['port'], $data['IP'], $data['ISP'], $data['DNS1'], $data['DNS2'] );
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        return $newsArray;
    }  

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'nic_passwd' );
    }

}