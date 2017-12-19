<?php

namespace Handler\Query;

class Exam extends EDU
{
    static $lifeTime = 3600 * 24 * 7;

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "姓名: %s", $data['name'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $s = '';
        foreach( $data['exams'] as $exam )
        {
            if( $s ) $s .= "\n\n";
            $s .= sprintf( "%s\n%s %s %s", $exam['datetime'], $exam['name'], $exam['address'], $exam['seatno'] );
        }
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        return $newsArray;
    }
}