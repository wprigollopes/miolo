<?php

class frmSpecialGrid extends MForm
{
    public $grid;

    public function __construct()
    {   
        parent::__construct(_M('Special Grid', MIOLO::getCurrentModule()));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        
        $columns[] = new MGridColumn(_M('Column @1', $module, '1'), MGrid::ALIGN_CENTER);
        $columns[] = new MGridColumn(_M('Column @1', $module, '2'), MGrid::ALIGN_CENTER);
        $columns[] = new MGridColumn(_M('Column @1', $module, '3'), MGrid::ALIGN_CENTER);
        $columns[] = new MGridColumn(_M('Column @1', $module, '4'), MGrid::ALIGN_CENTER);
        $columns[] = new MGridColumn(_M('Column @1', $module, '5'), MGrid::ALIGN_CENTER);

        /*$data[] = array('1:1', '1:2');
        $data[] = array('2:1', '2:2');
        $data[] = array('3:1', '3:2');*/
        
        $objAluno = $MIOLO->getBusiness('example', 'aluno');
        $query = $objAluno->listAttributes('idAluno,nome,idCurso');
        $data = $query->result;

        $this->grid = new MSpecialGrid($data, $columns, 'gridId', 50);
        $this->grid->setInvisibleColumns(array( 0, 3 ));
        
        $fields[] = $this->grid;
        $this->addFields($fields);

        $buttons[] = new MBackButton();
        $buttons[] = new MButton('btnSend', _M('Send', $module), ':send');

        $this->setButtons($buttons);
    }

    public function send($args)
    {
        $data = $this->grid->getSelectedData();
        new MMessage('<pre>'.print_r($data, 1).'</pre>');
    }
}
?>
