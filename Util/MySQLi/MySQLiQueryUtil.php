<?php

namespace Util\MySQLi;

use Util\MySQLi\MySQLiUtil;
use Util\Exceptions\MySQLiQueryUtilException;

class MySQLiQueryUtil
{
    static $cur;

    public $util;
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

    function __construct()
    {
        $args = func_get_args();
        $cnt = func_num_args();
        if( !$args ) throw new MySQLiQueryUtilException( '构造器:参数不合法' );
        if( $args[0] instanceof MySQLiUtil )
        {
            $this->util = $args[0];
            return;
        }
        if( $cnt == 3 ) $args[] = null;
        if( count( $args ) != 4 ) throw new MySQLiQueryUtilException( '构造器:参数不合法' );
        $this->util = MySQLiUtilPool::getInstance( $args[0], $args[1], $args[2], $args[3] );
    }

    /* 设置 select 部分
    select( 'f1' )  select( [ [ 'f1', 'f1alias' ], 'f2' ] ) select( [ 't1' => [ [ 'f1', 'f1alias' ], 'f2' ], 't2' => 'f2' ] )
    */
    function select( $v )
    {
        if( is_string( $v ) ) $v = [ $v ];
        if( !is_array( $v ) ) throw new MySQLiQueryUtilException( 'select:参数不合法' );
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

    //生成 select 部分sql
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
                    if( !is_string( $vv[1] ) ) throw new MySQLiQueryUtilException( '_select:参数不合法' );
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
                if( !is_string( $v[1] ) ) throw new MySQLiQueryUtilException( '_select:参数不合法' );
                $a[] = "{$v[0]} as {$v[1]}";
            }
        }
        return 'select ' . implode( ',', $a );
    }

    /*设置 from 或者 update 的表名部分
    table( 't1' )  table( [ 't1', 't2' ] ) 
    */
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
    
    function _from()
    {
        $t = $this->table;
        if( !$t ) throw new MySQLiQueryUtilException( '_from:至少指定一张表' );
        return ' from ' . implode( ',', $t );
    }

    function _update()
    {
        $t = $this->table;
        if( !$t ) throw new MySQLiQueryUtilException( '_update:至少指定一张表' );
        return 'update ' . implode( ',', $t );
    }

    /*设置 insert 的字段名部分
    field( 'f1' )  field( [ 'f1', 'f2' ] ) 
    */
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

    function _insert()
    {
        $tbl = $this->table; $fld = $this->field;
        if( !isset( $tbl[0] ) ) throw new MySQLiQueryUtilException( '_insert:请指定需要插入数据的表' );
        $fs = []; if( $fld ) foreach( $fld as $i ) $fs[] = "`{$i}`";
        $f_str = ''; if( $fs ) $f_str = '(' . implode( ',', $fs ) . ')';
        return "insert into `{$tbl[0]}`" . $f_str;
    }

    /*设置update set部分
    set( [ 'f1' => 'v1', 'f2' => 'v2', 'f3' => Raw( 'f3 + 1' ) ] )
    */
    function set( array $v )
    {
        $this->set = $v;
        return $this;
    }

    // 自动过滤 value支持rawsql
    function _set()
    {
        $set = $this->set; $a = [];
        foreach( $set as $k => $v )
        {
            if( $v instanceof UtilRaw )
            {
                $a[] = "{$k} = " . $v->text;
            }
            else
            {
                $v = $this->util->filter( $v );
                $r_v = 'null'; if( $v !== null ) $r_v = "'{$v}'";
                $a[] = "{$k} = {$r_v}";
            }
        }
        if( !$a ) throw new MySQLiQueryUtilException( '_set:至少更新一个字段' );
        return ' set ' . implode( ',', $a );
    }

    /*
    设置where部分
    where( 'f', 'v' ) where( 'f', 'operator', 'v' ) where( function( $query ) { $query->where...; } )
    */
    function where()
    {
        $a_c = func_num_args();
        if( !$a_c ) throw new MySQLiQueryUtilException( 'where:至少传递一个参数' );
        $args = func_get_args();
        $where = &$this->where;
        if( !$where )
        {
            $where = [];
            self::$cur = &$where;
        }
        $arg1 = $args[0];
        if( gettype( $arg1 ) == 'object' && get_class( $arg1 ) == 'Closure' )
        {
            if( self::$cur ) self::$cur[] = Raw( $this->orSwitch ? 'or' : 'and' );
            $old = &self::$cur;
            self::$cur[] = [];
            self::$cur =  &self::$cur[ count( self::$cur ) - 1 ];
            $args[0]( $this );
            self::$cur = &$old;
            return $this;
        }
        if( $args[0] instanceof UtilRaw )
        {
            if( self::$cur ) self::$cur[] = Raw( $this->orSwitch ? 'or' : 'and' );
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
        if( $val instanceof UtilRaw )
        {
            $val = $val->text;
        }
        else
        {
            $val = $this->util->filter( $val );
            $val = "'{$val}'";
        }
        if( self::$cur ) self::$cur[] = Raw( $this->orSwitch ? 'or' : 'and' );
        self::$cur[] = [ 'col' => $col, 'ope' => $ope, 'val' => $val ];
        return $this;
    }

    //用法和where相同 最后生成时前面跟的是or而不是and
    function orWhere()
    {
        $args = func_get_args();
        $this->orSwitch = true;
        $this->where( ...$args );
        $this->orSwitch = false;
        return $this;
    }

    //自动过滤(在where里)
    function _where()
    {
        $w = &$this->where;
        if( !$w ) return '';
        return ' where ' . $this->__where( $w );
    }

    //用于递归
    private function __where( &$a, $f = 0 )
    {
        $s = '';
        foreach( $a as &$v )
        {
            if( $s ) $s .= ' ';
            if( $v instanceof UtilRaw )
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

    /*
    order( 'f1' ) order( [ 'f1', [ 'f2', 0 ] ] )
    0表示降序
    */
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

    /*
    offset( 10 )
    */
    function offset( $v )
    {
        $v = (int) $v;
        if( $v < 0 ) $v = 0;
        $this->offset = $v;
        return $this;
    }

    /*
    offset( 20 )
    */
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

    /*
    生成insert sql语句  自动过滤
    _insert_( 'v1' )  _insert_( [ 'v1', 'v2' ] )  _insert_( [ [ 'v1', 'v2' ], [ 'v3', v4' ] ] )
    */
    function _insert_( $v, array $override = [] )
    {
        if( is_string( $v ) ) $v = [ $v ];
        if( !$v || !is_array( $v ) ) throw new MySQLiQueryUtilException( '_insert_:参数不合法' );
        if( !is_array( reset( $v ) ) ) $v = [ $v ];
        $f_c = 0; if( $this->field ) $f_c = count( $this->field );
        $a = [];
        foreach( $v as $i )
        {
            $aa = [];
            if( $f_c && $f_c != count( $i ) ) throw new MySQLiQueryUtilException( '_insert_:字段数和插入值列数不相符' );
            foreach( $i as $ii )
            {
                $ii = $this->util->filter( $ii );
                if( $ii === null ) $aa[] = 'null'; else $aa[] = "'{$ii}'";
            }
            $a[] = '(' . implode( ',', $aa ) . ')'; 
        }
        $sql = $this->_insert() . ' values ' . implode( ',', $a );
        $a = [];
        foreach( $override as $k => $v )
        {
            if( $v instanceof UtilRaw )
            {
                $a[] = "{$k} = " . $v->text;
            }
            else
            {
                $v = $this->util->filter( $v );
                $a[] = "{$k} = '{$v}'";
            }
        }
        if( $a ) $sql .= ' on duplicate key update ' . implode( ',', $a );
        $this->sql = $sql;
        return $sql;
    }

    function insert( $v, array $override = [] )
    {
        return $this->util->exec( $this->_insert_( $v, $override ) );
    }

    function _delete()
    {
        $sql = 'delete' . $this->_from() . $this->_where();
        $this->sql = $sql;
        return $sql;
    }

    function delete()
    {
        return $this->util->exec( $this->_delete() );
    }

    function _get()
    {
        $sql = $this->_select() . $this->_from() . $this->_where() . $this->_order() . $this->_limit();
        $this->sql = $sql;
        return $sql;
    }

    function get()
    {
        $a = $this->util->exec( $this->_get() );
        return Result( $a );
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
        return $this->util->exec( $this->_update_() );
    }

    function __destruct()
    {
    }
}

function Result( $a )
{
    return new UtilResult( $a );
}

class UtilResult
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

function Raw( $text )
{
    return new UtilRaw( $text );
}

class UtilRaw
{
    public $text;

    function __construct( $text )
    {
        $this->text = $text;
    }

    function __toString()
    {
        return $this->text;
    }
}