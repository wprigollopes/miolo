<?php

class MysqlQuery extends MQuery
{
    public $id_result;

    public function __construct()
    {
        parent::__construct();
    }

    public function _query()
    {
        $this->fetched = true;
        $this->id_result = mysqli_query($this->conn->id, $this->sql);

        if ($this->nrows = mysqli_num_rows($this->id_result))
        {
            $this->result = Array
                (
                );

            $row = $this->offset ? $this->offset : 0;
            $mrows = $this->maxrows ? (($this->maxrows < $this->nrows) ? $this->maxrows : $this->nrows) : $this->nrows;
            $n = 0;

            while ( ($n < $mrows) && (mysqli_data_seek($this->id_result, $row++)) )
            {
                $this->result[$n++] = mysqli_fetch_row($this->id_result);
            }


            $this->nrows = $this->rowCount = $n;
        }
    }

    public function _error()
    {
        return mysqli_error($this->conn->id);
        ;
    }

    public function _close()
    {
        if ($this->id_result != null)
        {
            mysqli_free_result($this->id_result);
            $this->id_result = null;
        }
    }

    function _setmetadata()
    {
        $numCols = mysqli_num_fields($this->id_result);

        $this->metadata = array();

        for ($i = 0; $i < $numCols; $i++)
        {
            $field = mysqli_fetch_field_direct($this->id_result, $i);
            $name = strtoupper($field->name);
            $this->metadata['fieldname'][$i] = $name;
            $this->metadata['fieldtype'][$name] = $this->_getmetatype($field->type);
            $this->metadata['fieldlength'][$name] = $field->length;
            $this->metadata['fieldpos'][$name] = $i;
        }
    }


    function _getmetatype($type)
    {
        switch ($type)
        {
            case MYSQLI_TYPE_DECIMAL:
            case MYSQLI_TYPE_NEWDECIMAL:
            case MYSQLI_TYPE_FLOAT:
            case MYSQLI_TYPE_DOUBLE:
            case MYSQLI_TYPE_BIT:
            case MYSQLI_TYPE_TINY:
            case MYSQLI_TYPE_SHORT:
            case MYSQLI_TYPE_LONG:
            case MYSQLI_TYPE_LONGLONG:
            case MYSQLI_TYPE_INT24:
            case MYSQLI_TYPE_YEAR:
            case MYSQLI_TYPE_ENUM:
                return 'N';

            case MYSQLI_TYPE_TIMESTAMP:
            case MYSQLI_TYPE_TIME:
            case MYSQLI_TYPE_DATETIME:
            case MYSQLI_TYPE_INTERVAL:
            case MYSQLI_TYPE_SET:
            case MYSQLI_TYPE_VAR_STRING:
            case MYSQLI_TYPE_STRING:
            case MYSQLI_TYPE_CHAR:
            case MYSQLI_TYPE_GEOMETRY:
                return 'C';

            case MYSQLI_TYPE_DATE:
            case MYSQLI_TYPE_NEWDATE:
                return 'T';
            default:
                return 'N';
        }
    }

}

?>