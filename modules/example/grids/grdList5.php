<?php

class grdList5 extends MGrid
{
    public function __construct($name = 'fullGrid')
    {
        global $state;

        // get an MIOLO instance
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        // inside grid column controls
        $selRights = new MSelection('selRights', '', '', $MIOLO->getPerms()->perms);

        // create the query to obtain the data
        // creates an aluno object
        $objAluno = $MIOLO->getBusiness('example', 'aluno');
        // execute the list of aluno.class
        $query = $objAluno->listAttributes('idAluno,nome,curso.nome', 'nome');

        $txtConstant = new MLabel('constant');

        // inside grid column link
        $href_aluno = $MIOLO->getActionURL('example', 'main:aluno', '#0#', array( 'event' => 'btnEdit:click' ));

        // define the grid's columns
        $columns = array(
            new MGridColumn(_M('Id', $module), 'right', true, '5%', false),
            new MGridHyperlink(_M('Name', $module), $href_aluno, '25%', true, null, true),
            new MGridControl($txtConstant, _M('A constant', $module), 'ControlColumn', false, '15%'),
            new MGridColumn(_M('Course', $module), 'left', true, '30%', true, null, false, true),
            new MGridControl($selRights, _M('Rights', $module), 'ControlColumn', false, '25%'),
        );

        // define a action
        $href_grid = $MIOLO->getActionURL('example', 'controls:grid5');

        // call the parent's constructor
        parent::__construct($query->result, $columns, $href_grid, 5, 0, $name, true, true);

        // set the grid width
        $this->setWidth('700px');

        // Change the column attribute
        // Ex: the attribute 'index' indicates wich column of the dataarray 
        // corresponds to the grid's column
        $this->setColumnAttr(2, 'index', null);
        $this->setColumnAttr(3, 'index', 2);

        // wich column is the grid index?
        $this->setIndex(0);

        // box title
        $this->setTitle($name);

        // create actions for the grid
        $href_edit = $MIOLO->getActionURL('example', 'main:aluno', '%0%', array( 'event' => 'btnEdit:click' ));
        $href_dele = $MIOLO->getActionURL('example', 'main:aluno', '%0%', array( 'event' => 'btnDelete:click' ));
        $this->addActionUpdate($href_edit);
        $this->addActionDelete($href_dele);
        $this->addActionText('alt', _M('Edit', $module), $href_edit);
        $this->addActionSelect();

        // invoke the eventHandler method
        $this->eventHandler();
    }
}
