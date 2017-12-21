<?php

namespace Handler\Query;

class Ecard extends Query
{
    public $useCache = false;
    public $startDate;
    public $endDate;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->ecard_passwd;
        if( $this->startDate && $this->endDate )
        {
            $args['startdate'] = $this->startDate;
            $args['enddate'] = $this->endDate;
        }
        return $args;
    }

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "%s(%s)", $data['sid'], $data['name'] );
        $head .= sprintf( "\n%s 至 %s 的消费数据", $data['startdate'], $data['enddate'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $s = sprintf( '账户余额: %s', $data['balance'] );
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        $s = ''; $sum = 0;
        foreach( $data['consumes'] as $consume )
        {
            $sum += doubleval( $consume['amount'] );
            if( $s ) $s .= "\n";
            $s .= sprintf( "%s %s %s  %s元", $consume['datetime'], $consume['station'], $consume['machine'], $consume['amount'] );
        }
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        $s = sprintf( '时间段内总消费: %.2f元', $sum );
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        return $newsArray;
    }

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'ecard_passwd' );
    }
}