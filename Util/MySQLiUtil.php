<?php

namespace Util;

use Util\MySQLiQueryUtil;
use Util\Exceptions\MySQLiUtilException;

class MySQLiUtil
{
    static $throw = true;           //是否抛出错误
    static $defaultTimeout = 2;     //默认超时 2 秒

    public $con;
    public $host;
    public $username;
    public $password;
    public $dbname;
    public $sql;
    public $affect;
    public $errorCode;
    public $errorMsg;

    static function errorCode2Msg( $code )
    {
        $msg = '';
        switch( $code )
        {
            case 1045:
                $msg = '账号信息有误';
                break;
            case 1046:
                $msg = '未选择数据库';
                break;
            case 1049:
                $msg = '数据库不存在';
                break;
            case 1064:
                $msg = 'SQL语法错误';
                break;
            case 1115:
                $msg = '未知的字符集';
                break;
            case 2002:
                $msg = '未知的主机';
                break;
            default:
                $msg = '未知的错误';
        }
        return $msg;
    }

    function __construct( $host, $username, $password, $dbname = null )
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        $con = &$this->con;
        $con = mysqli_init();
        $con->options( MYSQLI_OPT_CONNECT_TIMEOUT, self::$defaultTimeout );     //设置超时时间
        $con->real_connect( $host, $username, $password, $dbname );

        $errorCode = $this->errorCode = $con->connect_errno;
        $errorMsg = $this->errorMsg = $con->connect_error;
        if( $errorCode )
        {
            $errorMsg = self::errorCode2Msg( $errorCode );
            trigger_error( $this->__toString() );
            throw new MySQLiUtilException( $errorMsg );
        }

        $con->query( 'set names utf8' );      //编码UTF-8
    }

    function getQuery( $v = null )
    {
        $q = new MySQLiQueryUtil( $this );
        if( $v ) $q->table( $v );
        return $q;
    }
    
    //对特殊字符进行转义,防止SQL注入
    function filter( $str )
    {
        $str = stripslashes( $str );
        $str = $this->con->real_escape_string( $str );
        return $str;
    }

    //执行SQL
    function exec( $sql )
    {
        $con = &$this->con;
        $this->sql = $sql;
        $result = $con->query( $sql );
        $errorCode = $this->errorCode = $con->errno;
        $errorMsg = $this->errorMsg = $con->error;
        $this->affect = $con->affected_rows;
        
        $errorMsg = self::errorCode2Msg( $errorCode );
        if( $errorCode )
        {
            trigger_error( $this->__toString() );
            if( self::$throw )
                throw new MySQLiUtilException( $errorMsg );
        }

        if( gettype( $result ) == 'object' && get_class( $result ) == 'mysqli_result' )
        {
            $rows = array();
            while( $row = $result->fetch_assoc() )
            {
                $rows[] = $row;
            }
            return $rows;
        }

        return $result;
    }

    function databaseExists( $dbname )
    {
        $list = $this->getDatabaseList();
        foreach( $list as $i )
        {
            if( $i == $dbname ) return true;
        }
        return false;
    }

    function getDatabaseList()
    {
        $sql = 'show databases';
        $r = $this->exec( $sql );
        $list = array();
        foreach( $r as $i )
        {
            foreach( $i as $ii )
            {
                $list[] = $ii;
            }
        }
        return $list;
    }

    function tableExists( $tablename )
    {
        $list = $this->getTableList();
        foreach( $list as $i )
        {
            if( $i == $tablename ) return true;
        }
        return false;
    }

    function getTableList()
    {
        $sql = 'show tables';
        $r = $this->exec( $sql );
        $list = array();
        foreach( $r as $i )
        {
            foreach( $i as $ii )
            {
                $list[] = $ii;
            }
        }
        return $list;
    }

    function __toString()
    {
        return implode( ',', array( $this->host, $this->username, $this->dbname, $this->sql, $this->affect, $this->errorCode, $this->errorMsg ) );
    }

    function close()
    {
        $con = &$this->con;
        if( empty( $con ) ) return;
        mysqli_close( $con );
        $con = null;
    }

    function __destruct()
    {
        $this->close();
    }
}


