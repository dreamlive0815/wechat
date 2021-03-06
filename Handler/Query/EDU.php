<?php

namespace Handler\Query;

class EDU extends Query
{
    static $thisYear = '2017-2018';
    static $thisSemester = '1';

    public $year;
    public $semester;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->edu_passwd;
        if( $this->year && $this->semester )
        {
            $args['year'] = $this->year;
            $args['semester'] = $this->semester;
        }
        return $args;
    }

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'edu_passwd' );
    }
}