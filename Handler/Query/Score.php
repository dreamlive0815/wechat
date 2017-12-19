<?php

namespace Handler\Query;

class Score extends EDU
{
    static $lifeTime = 3600 * 12;

    public $currentSemester = false;

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "%s(%s)", $data['sid'], $data['name'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $scores = $data['container'];
        $group = [];
        foreach( $scores as $k => $v )
        {
            $y = $v['year']; $s = $v['semester'];
            $group[$y][$s][] = $k;
        }

        $sumc = 0; $sumcmp = 0;
        foreach( $group as $year => $v )
        {
            $s = '';//一整个学期一个news
            $sumcy = 0; $sumcmpy = 0;
            foreach( $v as $semester => $vv )
            {
                $ss = "{$year} 学年第 {$semester} 学期";
                $sumcs = 0; $sumcmps = 0;
                foreach( $vv as $index )
                {
                    $score = $scores[$index];
                    $credit = doubleval( $score['credit'] );
                    $point = doubleval( $score['point'] );
                    if( self::calFilter( $score ) )
                    {
                        $sumcs += $credit; $sumcy += $credit; $sumc += $credit;
                        $cmp = $credit * $point;
                        $sumcmps += $cmp; $sumcmpy += $cmp; $sumcmp += $cmp;
                    }

                    $ss .= sprintf( "\n%s(%s学分) %s(%s)", $score['name'], $score['credit'], $score['point'], $score['grade'] );
                }
                $ss .= sprintf( "\n学期总学分: %.2f\n学期平均绩点: %.2f", $sumcs, $sumcmps / $sumcs );
                if( $this->currentSemester && ( $year != self::$thisYear || $semester != self::$thisSemester ) ) continue;
                if( $s ) $s .= "\n\n";
                $s .= $ss;
            }
            if( !$this->currentSemester && $s )
            {
                $s .= sprintf( "\n\n学年总学分: %.2f\n学年平均绩点: %.2f", $sumcy, $sumcmpy / $sumcy );
            }
            if( $s ) $newsArray[] = $this->getNews( [ 'title' => $s ] );
        }

        if( !$this->currentSemester )
        {
            $s = sprintf( "总学分: %.2f\n 平均绩点: %.2f", $sumc, $sumcmp / $sumc );
            $newsArray[] = $this->getNews( [ 'title' => $s ] );
        }
        
        return $newsArray;
    }

    static function calFilter( $score )
    {
        if( $score['note'] == '放弃' ) return false;
        return true;
    }
}