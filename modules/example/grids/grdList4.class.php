<?php

class grdList4 extends MActiveGrid
{
	function __construct()
    {   
        $MIOLO = MIOLO::getInstance(); 
        $listURL = $MIOLO->getCurrentURL();
        $objTransaction = $MIOLO->getBusiness('admin','transaction');
        $query = $objTransaction->listAll();
        $columns = array(
           new MGridColumn('Id', 'right', true, '10%',true,null,false,true),
           new MGridColumn('Transaction', 'left', true, '90%',true,null,false,true),
        );
        parent::__construct($query->result, $columns, $listURL,15,0);
        $this->setTitle('Grid of Transactions [ActiveGrid]');
 	    $href_edit = $MIOLO->getActionURL('admin','main:transaction','%0%', array('event'=>'btnEdit:click'));
	    $href_dele = $MIOLO->getActionURL('admin','main:transaction','%0%', array('event'=>'btnDelete:click'));
        $this->addActionUpdate($href_edit);
	    $this->addActionDelete($href_dele);
        $this->addActionIcon('ID','select.gif',$action);
        $this->addActionText('alt','Editar',$href_edit);
	}
}
