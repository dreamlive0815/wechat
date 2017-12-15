<?php

namespace Handler\Query;

class Course extends Query
{
    static $single = false;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->edu_passwd;
        return $args;
    }

    function renderData( $data )
    {
        return $this->getRedirectSettingNews();
    }
}