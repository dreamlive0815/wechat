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

        return $newsArray;
    }  

    function onValidateError( $errorMsg )
    {
        return $this->getRedirectSettingNews( 'lib_passwd' );
    }
}