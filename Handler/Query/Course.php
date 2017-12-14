<?php

namespace Handler\Query;

class Course extends Query
{
    static function buildArgs( $user )
    {
        return [ 'passwd' => $user->edu_passwd ];
    } 
}