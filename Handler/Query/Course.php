<?php

namespace Handler\Query;

class Course extends EDU
{
    static $lifeTime = 3600 * 24 * 7;

    public $today = false;
    public $year;
    public $semester;

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "%s 学年第 %s 学期 %s 周", $data['year'], $data['semester'], self::getWeekNumber() );
        $head .= sprintf( "\n%s(%s)", $data['sid'], $data['name'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $container = [];
        for( $i = 1; $i <= 7; ++$i ) $container[$i] = [];
        foreach( $data['courses'] as $course )
        {
            $weekd = intval( $course['lessionweek'] );
            $container[$weekd][] = $course;
        }

        $weekday = self::getWeekday();
        for( $i = 1; $i <= 5; ++$i )
        {
            if( $this->today && $i != $weekday ) continue;
            $s = '周' . self::transWeekday( $i );
            if( !$container[$i] )
            {
                $s .= "\n\n无课";
            }
            foreach( $container[$i] as $c )
            {
                $s .= sprintf( "\n\n%s[%s]\n%s %s-%s周 %s-%s节", $c['name'], $c['teachername'], $c['address'], $c['startweek'], $c['endweek'], $c['lessionstarttime'], $c['lessionendtime'] );
            }
            $newsArray[] = $this->getNews( [ 'title' => $s ] );
        }
        
        return $newsArray;
    }
}