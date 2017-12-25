<?php

namespace Handler\Query;

class Borrow extends Query
{
    static $lifeTime = 3600 * 12;

    function buildArgs()
    {
        $args = parent::buildArgs();
        $args['passwd'] = $this->user->lib_passwd;
        return $args;
    }

    function renderData( $data )
    {
        $newsArray = [];
        $head = sprintf( "%s(%s)", $data['sid'], $data['name'] );
        $head .= $this->getStatusText();
        $newsArray[] = $this->getNews( [ 'title' => $head ] );

        $s = sprintf( "欠款金额: %.2f元\n当前借阅书数: %s\n最大可借书数: %s\n违章次数: %s", $data['debt'], count( $data['borrows'] ), $data['maxborrow'], $data['breachtimes'] );
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        $note = ''; $now = time();
        $s = '借阅详情:';
        if( !$data['borrows'] ) $s .= "\n\n当前还没有借阅的书籍";
        foreach( $data['borrows'] as $borrow )
        {
            $time = strtotime( $borrow['returndate'] );
            if( $now > $time )
            {
                $note .= sprintf( "\n书籍 %s 已到归还日期,请尽快归还或续借", $borrow['name'] );
            }
            $s .= sprintf( "\n\n%s\n%s\n借阅: %s  应还: %s", $borrow['name'], $borrow['author'], $borrow['borrowdate'], $borrow['returndate'] );
        }
        $newsArray[] = $this->getNews( [ 'title' => $s ] );

        if( $note )
        {
            $note = '提醒:' . $note;
            $newsArray[] = $this->getNews( [ 'title' => $note ] );
        }

        $newsArray[] = $this->getViewNews();

        return $newsArray;
    }  

    function renderView( $data )
    {
        $uls = [];
        $head = sprintf( "%s(%s)", $data['sid'], $data['name'] );
        $head .= str_replace( "\n", '<br />', $this->getStatusText() );
        $uls[] = [ 'args' => [], 'lis' => [ $head ] ];

        $uls[] = [ 'args' => [], 'lis' => [
            sprintf( '欠款金额: %.2f元', $data['debt'] ),
            '当前借阅书数: ' . count( $data['borrows'] ),
            '最大可借书数: ' . $data['maxborrow'],
            '违章次数: ' . $data['breachtimes'],
        ] ];

        $notelis = []; $now = time();
        $lis = [ '借阅详情' ];
        if( !$data['borrows'] ) $lis[] = '【当前还没有借阅的书籍】';
        foreach( $data['borrows'] as $borrow )
        {
            $time = strtotime( $borrow['returndate'] );
            if( $now > $time )
            {
                $notelis[] = sprintf( '书籍【%s】已到归还日期,请尽快归还或续借', $borrow['name'] );
            }
            $lis[] = sprintf( '%s<br />%s<br />借阅: %s  应还: %s', $borrow['name'], $borrow['author'], $borrow['borrowdate'], $borrow['returndate'] );
        }
        $uls[] = [ 'args' => [], 'lis' => $lis ];

        if( $notelis )
        {
            array_unshift( $notelis, '提醒' );
            $uls[] = [ 'args' => [], 'lis' => $notelis ];
        }

        return $uls;
    } 

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'lib_passwd' );
    }
}