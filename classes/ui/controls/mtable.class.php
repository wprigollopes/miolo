<?php

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Table
#    Base Class for html tables
#    - Rows, Cols and Content are 0-based
#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

class MTable extends MControl
{
    public $body = array();
    public $head;
    public $foot = array();
    public $colgroup;
    public $attr;

    public function __construct($body=array(), $tableAttr=NULL, $attr=NULL, $head=NULL, $foot=array(), $colgroup=NULL)
    {
        parent::__construct();
        $this->body = (array) $body;
        $this->head = $head;
        $this->foot = (array) $foot;
        $this->colgroup = $colgroup;
        $this->setAttributes($tableAttr);
        $this->attr = $attr;
    }

    public function setBody($value)
    {
        $this->body = (array) $value;
    }

    public function setHead($value)
    {
        $this->head = $value;
    }

    public function setFoot($value)
    {
        $this->foot = (array) $value;
    }

    public function setColGroup($value)
    {
        $this->colgroup = $value;
    }

    public function setAttr($value)
    {
        $this->attr = $value;
    }


}

?>