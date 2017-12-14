<?php

namespace Handler\Query;

class Course extends Query
{
    static function buildArgs( $user )
    {
        $args = parent::buildArgs( $user );
        $args['passwd'] = $user->edu_passwd;
        return $args;
    } 
}