<?php

namespace Control;

use Model\User;
use Config\Config;

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
}