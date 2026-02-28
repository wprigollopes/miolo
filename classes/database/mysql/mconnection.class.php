<?php

class MysqlConnection extends MConnection
{
    public function __construct($conf)
    {
        parent::__construct($conf);
    }

    public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent=TRUE, $parameters=NULL, $port='3306')
    {
        $this->id = mysqli_connect($dbhost, $loginUID, $loginPWD, $loginDB, $port);
        mysqli_select_db($this->id, $loginDB);
    }

    protected function buildDsn($dbhost, $loginDB, $port = null)
    {
        $h = explode(':', $dbhost);
        $host = $h[0];
        if (!$port) {
            $port = isset($h[1]) ? $h[1] : '3306';
        }
        return "mysql:host={$host};port={$port};dbname={$loginDB};charset=utf8";
    }

    public function _close()
    {
        mysqli_close($this->id);
    }

    public function _begintransaction()
    {
        $this->_execute("begin transaction");
    }

    public function _commit()
    {
        $this->_execute("commit");
    }

    public function _rollback()
    {
        $this->_execute("rollback");
    }

    public function _error()
    {
        return mysqli_error($this->id);
    }

    public function _execute($sql)
    {
        $rs = mysqli_query($this->id, $sql);
        $success = false;

        if ($rs)
        {
            $success = true;
            mysqli_free_result ($rs);
        }
        else
        {
            $this->traceback[] = $this->getError();
        }

        return $success;
    }

    /**
     * @return MysqlQuery Database query object.
     */
    public function _createquery()
    {
        return new MysqlQuery();
    }

    public function _chartotimestamp($timestamp)
    {
        return " TO_DATE(" . $timestamp . ",'DD/MM/YYYY HH24:MI:SS') ";
    }

    public function _chartodate($date)
    {
        return " TO_DATE(" . $date . ",'DD/MM/YYYY') ";
    }

    public function _timestamptochar($timestamp)
    {
        return " TO_CHAR(" . $timestamp . ",'DD/MM/YYYY HH24:MI:SS') ";
    }

    public function _datetochar($date)
    {
        return " TO_CHAR(" . $date . ",'DD/MM/YYYY') ";
    }

    public function _sqljoin(&$sql, $table1, $table2, $cond)
    {
        if ($sql->join)
        {
            $sql->join = "($sql->join INNER JOIN $table2 ON ($cond))";
        }
        else
        {
            $sql->join = "($table1 INNER JOIN $table2 ON ($cond))";
        }
    }

    public function _sqlleftjoin(&$sql, $table1, $table2, $cond)
    {
        if ($sql->join)
        {
            $sql->join = "($sql->join LEFT JOIN $table2 ON ($cond))";
        }
        else
        {
            $sql->join = "($table1 LEFT JOIN $table2 ON ($cond))";
        }
    }

    public function _sqlrightjoin(&$sql, $table1, $table2, $cond)
    {
        if ($sql->join)
        {
            $sql->join = "($sql->join RIGHT JOIN $table2 ON ($cond))";
        }
        else
        {
            $sql->join = "($table1 RIGHT JOIN $table2 ON ($cond))";
        }
    }
}
?>
