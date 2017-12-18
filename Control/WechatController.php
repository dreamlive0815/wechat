<?php

namespace Control;

class WechatController extends Controller
{
    public $menu;

    function __construct()
    {
        parent::__construct();
        require( __DIR__ . '/../wechat_head.php' );
        $this->menu = $GLOBALS['app']->menu;
    }

    function getMenusAction()
    {
        
        $menu = $this->menu;
        $menus = $menu->all();
        print_r( $menus );
    }
}