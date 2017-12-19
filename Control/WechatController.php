<?php

namespace Control;

use Model\User;
use Model\Reply;
use Config\Config;
use EasyWeChat\Message\News;

class WechatController extends Controller
{
    public $menu;
    public $user;

    function __construct()
    {
        parent::__construct();
        require( __DIR__ . '/../wechat_head.php' );
        $this->menu = $GLOBALS['app']->menu;
    }

    private function validate()
    {
        $openid = $this->getOpenid();
        $user = User::getUser( $openid );
        if( !$user->id || !( intval( $user->usertype ) & 8 ) ) throw new \Exception( '权限不足' );
        $this->user = $user;
    }

    function getMenusAction()
    {
        $this->validate();

        $menu = $this->menu;
        $menus = $menu->all();
        return $this->output( 0, '', $menus->toArray() );
    }

    function setMenusAction()
    {
        $this->validate();

        $conf = Config::get( 'Menu' );
        $menu = $this->menu;
        $menu->add( $conf->toArray() );
        return $this->output();
    }

    function removeMenusAction()
    {
        $this->validate();

        $menu = $this->menu;
        $menu->destroy();
        return $this->output();
    }

    function addNewsReplyAction()
    {
        $this->validate();
        
        $conf = Config::get( 'NewsReply' );
        $key = 'Touhou';
        $newsArray = [];
        foreach( $conf->newsArray as $news )
        {
            $newsArray[] = new News( $news );
        }
        Reply::updateReply( $conf->key, [ 'type' => 'news', 'data' => addslashes( serialize( $newsArray ) ) ] );
        return $this->output();
    }
}