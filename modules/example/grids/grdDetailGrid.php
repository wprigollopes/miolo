<?php

/*
  A DetailGrid is a AJAX-based grid
 */

class grdDetailGrid extends MGridAJAX
{

    public function __construct()
    {
        // get an MIOLO instance
        $MIOLO = MIOLO::getInstance();

        // register the methods callable by CPAINT
        // methods with prefix 'ajax' really not need to be registered - it is registered automagically
//        $this->registerMethod('ajax_detail');
        // testing if we have a AJAX call - if yes, return; cpaint already did the job...
//        if ($this->start()) 
//           return;
        // well, it's not a AJAX call, let's build the grid
        // create the query to obtain the data
        $curso = $MIOLO->getBusiness('example', 'curso');
        $query = $curso->listAll();

        // define the grid's columns
        $columns[] = new MGridColumn('Id', 'right', true, '5%', true);
        $columns[] = new MGridColumn('Curso', 'left', true, '90%', true);

        // define the grid url
        $href_grid = $MIOLO->getActionURL('example', 'controls:detailgrid');

        // call the parent's constructor
        parent::__construct($query->result, $columns, $href_grid, 5, 0, 'fullGrid');
        $this->setClose(NULL);

        // add javascript functions used by this grid
        $url = $this->manager->getActionURL('example', 'scripts:grdDetailGrid.js');
        $this->page->addScriptURL($url);

        // box title
        $this->setTitle('Grid of Cursos [A array ajax-grid with details]');

        // create grid action - icons to Show/Hide Details 
        // ajaxDetail.call is javascript function used by CPAINT
        // %r% - replaced by rowNumber
        // %0% - replaced by the value of column 0 at each row
        // %s% - replaced by 0 or 1 to show/hide the details
        $href_detail = "ajaxDetail.call(%r%,%0%,%s%);";
        $this->addActionDetail($href_detail);

        // put this grid in the theme
        $this->manager->getTheme()->insertContent($this);
        $this->eventHandler();
    }

    public function ajax_detail($args)
    {
        // this method is called by CPAINT at doDetail execution
        $MIOLO = MIOLO::getInstance();
        $id = $args->id;
        $this->manager->trace("ID = " . $id);
        $curso = $MIOLO->getBusiness('example', 'curso', $id);
        $query = $curso->listAlunos('M');
        $control = new MTableRaw('', $query->result, $query->getColumnNames());
        $control->setAlternate(true);
        // response_type = TEXT : set the content area of theme with the control
        $MIOLO->getTheme()->setAjaxContent($control);
    }
}
