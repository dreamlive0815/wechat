<?php

namespace Handler\Query;

class Course extends Query
{
    static $lifeTime = 3600 * 24 * 7;

    public $today = false;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->edu_passwd;
        return $args;
    }

    function renderData( $data )
    {
        return 'hhh';
    }

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'edu_passwd' );
    }
}