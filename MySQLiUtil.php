<?php
namespace Util;

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

    function query( $v = null )
    {
        /*
        $q = new Query( $this );
        if( $v ) $q->table( $v );
        return $q;
        */
    }
    
    //对特殊字符进行转义,防止SQL注入
    function filter( $str )
    {
        if( !is_string( $str ) ) return '';
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
/*
class Query
{
    static $cur;

    public $DB;
    public $sql;
    public $select;
    public $table;
    public $field;
    public $set;
    public $where;
    public $orSwitch = false;
    public $order;
    public $offset;
    public $limit;

    function __construct(DB &$DB)
    {
        $this->DB = $DB;
    }

    function select( $v )
    {
        if( is_string( $v ) ) $v = [ $v ];
        if( !is_array( $v ) ) throw new EX( 'DB_Query', 1, 'select:参数不合法' );
        $this->select = $v;
        if( $v )
        {
            $keys = array_keys( $v );
            if( is_string( $keys[0] ) )
            {
                $this->table( $keys );
            }
        }
        return $this;
    }

    function _select()
    {
        $sel = $this->select;
        if( !$sel ) return 'select *';
        $s = ''; $a = [];
        if( is_string( array_keys( $sel )[0] ) )
        {
            foreach( $sel as $k => $v )
            {
                if( is_string( $v ) )
                {
                    $a[] = "{$k}.{$v}";
                    continue;
                }
                foreach( $v as $vv )
                {
                    if( is_string( $vv ) )
                    {
                        $a[] = "{$k}.{$vv}";
                        continue;
                    }
                    if( !is_string( $vv[1] ) ) new EX( 'DB_Query', 1, 'select:参数不合法' );
                    $a[] = "{$k}.{$vv[0]} as {$vv[1]}";
                }

            }
        }
        else
        {
            foreach( $sel as $v )
            {
                if( is_string( $v ) )
                {
                    $a[] = $v;
                    continue;
                }
                if( !is_string( $v[1] ) ) new EX( 'DB_Query', 1, 'select:参数不合法' );
                $a[] = "{$v[0]} as {$v[1]}";
            }
        }
        return 'select ' . implode( ',', $a );
    }

    function table( $v )
    {
        $t = &$this->table; $t = [];
        if( is_array( $v ) )
        {
            foreach( $v as $i ) if( is_string( $i ) ) $t[] = $i;
        }
        else
        {
            if( is_string( $v ) ) $t[] = $v;
        }
        return $this;
    }

    function field( $v )
    {
        $f = &$this->field; $f = [];
        if( is_array( $v ) )
        {
            foreach( $v as $i ) if( is_string( $i ) ) $f[] = $i;
        }
        else
        {
            if( is_string( $v ) ) $f[] = $v;
        }
        return $this;
    }

    function _from()
    {
        $t = $this->table;
        if( !$t ) throw new EX( 'DB_Query', 1, '至少指定一张表' );
        return ' from ' . implode( ',', $t );
    }

    function _update()
    {
        $t = $this->table;
        if( !$t ) throw new EX( 'DB_Query', 1, '至少指定一张表' );
        return 'update ' . implode( ',', $t );
    }

    function _insert()
    {
        $tbl = $this->table; $fld = $this->field;
        if( !isset( $tbl[0] ) ) throw new EX( 'DB_Query', 1, '请指定需要插入数据的表' );
        $fs = []; if( $fld ) foreach( $fld as $i ) $fs[] = "`{$i}`";
        $f_str = ''; if( $fs ) $f_str = '(' . implode( ',', $fs ) . ')';
        return "insert into `{$tbl[0]}`" . $f_str;
    }

    function set( array $v )
    {
        $this->set = $v;
        return $this;
    }

    function _set()
    {
        $set = $this->set; $a = [];
        foreach( $set as $k => $v )
        {
            if( $v instanceof RawText )
            {
                $a[] = "{$k} = " . $v->text;
            }
            else
            {
                $v = $this->DB->filter( $v );
                $a[] = "{$k} = '{$v}'";
            }
        }
        if( !$a ) throw new EX( 'DB_Query', 1, '至少更新一个字段' );
        return ' set ' . implode( ',', $a );
    }

    function where()
    {
        $a_c = func_num_args();
        if( !$a_c ) throw new EX( 'DB_Query', 1, '至少传递一个参数' );
        $args = func_get_args();
        $where = &$this->where;
        if( !$where )
        {
            $where = [];
            self::$cur = &$where;
        }
        if( $args[0] instanceof Closure )
        {
            if( self::$cur ) self::$cur[] = DB_Raw( $this->orSwitch ? 'or' : 'and' );
            $old = &self::$cur;
            self::$cur[] = [];
            self::$cur =  &self::$cur[ count( self::$cur ) - 1 ];
            $args[0]( $this );
            self::$cur = &$old;
            return $this;
        }
        if( $args[0] instanceof RawText )
        {
            if( self::$cur ) self::$cur[] = DB_Raw( $this->orSwitch ? 'or' : 'and' );
            self::$cur[] = $args[0];
            return $this;
        }
        $col = $args[0];
        $ope = '=';
        if( $a_c == 3 )
        {
            if( in_array( $args[1], [ '=', 'Like', '>', '<', '>=', '<=', '<>' ] ) ) $ope = $args[1];
            $val = $args[2];
        }
        else
        {
            $val = $args[1];
        }
        if( $val instanceof RawText )
        {
            $val = $val->text;
        }
        else
        {
            $val = $this->DB->filter( $val );
            $val = "'{$val}'";
        }
        if( self::$cur ) self::$cur[] = DB_Raw( $this->orSwitch ? 'or' : 'and' );
        self::$cur[] = [ 'col' => $col, 'ope' => $ope, 'val' => $val ];
        return $this;
    }

    function orWhere()
    {
        $args = func_get_args();
        $this->orSwitch = true;
        $this->where( ...$args );
        $this->orSwitch = false;
        return $this;
    }

    function _where()
    {
        $w = &$this->where;
        if( !$w ) return '';
        return ' where ' . $this->__where( $w );
    }

    private function __where( &$a, $f = 0 )
    {
        $s = '';
        foreach( $a as &$v )
        {
            if( $s ) $s .= ' ';
            if( $v instanceof RawText )
            {
                $s .= $v->text;
            }
            else
            {
                if( isset( $v['col'] ) )
                {
                    $s .= $v['col'] . ' ' . $v['ope'] . ' ' . $v['val'];
                }
                else
                {
                    $s .= $this->__where( $v, 1 );
                }
            }
        }
        if( $f ) $s = "( {$s} )";
        return $s;
    }

    function order( $v )
    {
        if( is_string( $v ) ) $v = [ $v ];
        $this->order = $v;
        return $this;
    }

    function _order()
    {
        $o = $this->order;
        if( !$o ) return '';
        $s = ''; $a = [];
        foreach( $o as $v )
        {
            if( is_string( $v ) )
            {
                $a[] = $v;
                continue;
            }
            $odr = 'asc';
            if( isset( $v[1] ) && !$v[1] ) $odr = 'desc';
            $a[] = "{$v[0]} {$odr}";
        }
        return ' order by ' . implode( ',', $a );
    }

    function offset( $v )
    {
        $v = (int) $v;
        if( $v < 0 ) $v = 0;
        $this->offset = $v;
        return $this;
    }

    function limit( $v )
    {
        $v = (int) $v;
        if( $v < 0 ) $v = 0;
        $this->limit = $v;
        return $this;
    }

    function _limit()
    {
        $off = $this->offset;
        $lmt = $this->limit;
        if( $off === null && $lmt === null ) return '';
        if( $off === null ) $s = $lmt;
        else if( $lmt === null ) $s = $off . ',-1';
        else $s = $off . ',' . $lmt;
        $s = ' limit ' . $s;
        return $s;
    }

    function _insert_( $v, array $override = [] )
    {
        if( is_string( $v ) ) $v = [ $v ];
        if( !$v || !is_array( $v ) ) new EX( 'DB_Query', 1, 'insert:参数不合法' );
        if( !is_array( reset( $v ) ) ) $v = [ $v ];
        $f_c = 0; if( $this->field ) $f_c = count( $this->field );
        $a = [];
        foreach( $v as $i )
        {
            $aa = [];
            if( $f_c && $f_c != count( $i ) ) throw new EX( 'DB_Query', 1, '字段数和插入值列数不相符' );
            foreach( $i as $ii )
            {
                $ii = $this->DB->filter( $ii );
                $aa[] = "'{$ii}'";
            }
            $a[] = '(' . implode( ',', $aa ) . ')'; 
        }
        $sql = $this->_insert() . ' values ' . implode( ',', $a );
        $a = [];
        foreach( $override as $k => $v )
        {
            if( $v instanceof RawText )
            {
                $a[] = "{$k} = " . $v->text;
            }
            else
            {
                $v = $this->DB->filter( $v );
                $a[] = "{$k} = '{$v}'";
            }
        }
        if( $a ) $sql .= ' on duplicate key update ' . implode( ',', $a );
        $this->sql = $sql;
        return $sql;
    }

    function insert( $v, array $override = [] )
    {
        return $this->DB->exec( $this->_insert_( $v, $override ) );
    }

    function _delete()
    {
        $sql = 'delete' . $this->_from() . $this->_where();
        $this->sql = $sql;
        return $sql;
    }

    function delete()
    {
        return $this->DB->exec( $this->_delete() );
    }

    function _get()
    {
        $sql = $this->_select() . $this->_from() . $this->_where() . $this->_order() . $this->_limit();
        $this->sql = $sql;
        return $sql;
    }

    function get()
    {
        $a = $this->DB->exec( $this->_get() );
        return DB_Result( $a );
    }

    function count()
    {
        return (int) $this->select( 'count(*)' )->get()->val();
    }

    function _update_()
    {
        $sql = $this->_update() . $this->_set() . $this->_where();
        $this->sql = $sql;
        return $sql;
    }

    function update()
    {
        return $this->DB->exec( $this->_update_() );
    }

    function __destruct()
    {
    }
}

function DB_Result( $a )
{
    return new Result( $a );
}

class Result
{
    public $a;

    function __construct( $a )
    {
        $this->a = $a;
    }

    function val()
    {
        if( !$this->a ) return false;
        $arr = $this->a[0];
        return reset( $arr );
    }

    function fir()
    {
        if( !$this->a ) return false;
        return $this->a[0];
    }
}


function DB_Raw( $text )
{
    return new RawText( $text );
}

class RawText
{
    public $text;

    function __construct( $text )
    {
        $this->text = $text;
    }
}
*/

class MySQLiUtilException extends \Exception
{
	function __construct( $errorMsg, $errorCode = 10002 )
    {
        parent::__construct( $errorMsg, $errorCode );
    }
}

?>