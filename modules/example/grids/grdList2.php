<?php

/**
 *
 */
class grdList2 extends MGrid
{
    /**
     *
     */
    public function __construct()
    {
        // get an instance of MIOLO
        $MIOLO = MIOLO::getInstance();

        // get the url of the current page
        $listURL = $MIOLO->getCurrentURL();

        // creates an aluno object
        $objAluno = $MIOLO->getBusiness( 'example', 'aluno');
        // execute the list of aluno.class
        $query   = $objAluno->listAttributes('idAluno,nome');

        // define columns for the grid
        $columns = array( new MGridColumn( 'Id', 'right', true, '7%', true, null, false, true ),
                          new MGridColumn( _M('Full name'), 'left', true, '90%', true, null, false, true ),
                         );

        // execute the parent's constructor
        parent::__construct( $query->result, $columns, $listURL, 15, 0 );

        // get icons from a css file with custom images
        $css = $this->manager->getModulePath( 'example', $this->manager->getConf('home.module.html') . '/css/example_icons.css'  );
        $this->manager->getUI()->loadIcons($css); 

        // set the grid's title
        $this->setTitle( _M('Grid of students') );

        // define actions
        $href_edit = $MIOLO->getActionURL( 'example', 'main:aluno', '%0%', array('event'=>'btnEdit:click') );
        $href_dele = $MIOLO->getActionURL( 'example', 'main:aluno', '%0%', array('event'=>'btnDelete:click') );


        // add actions to the grid
        $this->addActionUpdate( $href_edit );
        $this->addActionDelete( $href_dele );
        $this->addActionIcon( 'ID','select.gif',$href_edit );
        $this->addActionText('alt', _M('Edit'), $href_edit );

        // add action with custom image - path of image is inside CSS file
        $this->addActionIcon('ID', _M('properties'), $href_edit);

    }
}
