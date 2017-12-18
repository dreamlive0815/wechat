<?php

namespace Handler\Query;

class Score extends EDU
{
    static $lifeTime = 3600 * 12;

    function renderData( $data )
    {
        
        return 'Score';
    }
}