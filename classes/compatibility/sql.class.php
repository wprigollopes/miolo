<?php
class sql extends MSQL
{
    public function sql($columns = '', $tables = '', $where = '', $orderBy = '', $groupBy = '', $having = '')
    {
        self::__construct($columns,$tables,$where,$orderBy,$groupBy,$having);
    }

    public function __construct($columns = '', $tables = '', $where = '', $orderBy = '', $groupBy = '', $having = '')
    {
        parent::__construct($columns,$tables,$where,$orderBy,$groupBy,$having);
    }
}
?>