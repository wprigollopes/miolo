<?php

class grdList3 extends MGrid
{
    public $alunos;

    public function __construct()
    {
        global $state;

        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        // footer controls
        $selPerms = new MSelection('selPerms', _M('Choose a permission', $module), _M('Rights', $module), $MIOLO->getPerms()->perms);
        $ctlArray = array(
            new MButton('btnFilter', _M('Filter on/off', $module)),
            new MButton('btnShow', _M('Show selected page/lines', $module)),
            new MButton('btnAlternate', _M('Alternate background colors', $module)),
            $selPerms
        );

        // create the query to obtain the data
        // creates an aluno object
        $objAluno = $MIOLO->getBusiness('example', 'aluno');
        // execute the list of aluno.class
        $query = $objAluno->listAttributes('idAluno,nome,curso.nome', 'nome');

        $txtConstant = new MLabel('');

        // inside grid column link
        $href_aluno = $MIOLO->getActionURL('example', 'main:aluno', '#0#', array( 'event' => 'btnEdit:click' ));

        // inside grid column control
        $selRights = new MSelection('selRights', '', '', $MIOLO->getPerms()->perms);

        $hrefWindow = $MIOLO->getActionURL('example', 'main:aluno', '%0%', array( 'event' => 'btnEdit:click' ));
        $btnWindow = new MButtonWindow('btnWindow', _M('New window', $module), $hrefWindow, 'target_report');


        // define the grid's columns
        $columns = array(
            new MGridColumn(_M('Id', $module), 'right', true, '5%', false),
            new MGridHyperlink(_M('Student', $module), $href_aluno, '35%', true, null, true),
            new MGridControl($txtConstant, _M('First letter', $module), 'center', false, '15%'),
            new MGridColumn(_M('Course', $module), 'left', true, '30%', true, null, false, true),
            new MGridControl($selRights, _M('Rights', $module), 'center', false, '25%'),
            new MGridControl($btnWindow, _M('Open window', $module), 'center', false, '25%'),
        );

        // add a filter field
        // the first element indicates the index of the filter field
        $this->addFilterText(1, _M('Name', $module), '');

        // define a action
        $href_grid = $MIOLO->getActionURL('example', 'controls:grid3');
        // set the data
        $this->alunos = $query->result;
        // call the parent's constructor
        parent::__construct($this->alunos, $columns, $href_grid, 15, 0, 'fullGrid', true, true);

        // grid width
        $this->setWidth('650px');

        // Change the column attribute
        // Ex: the attribute 'index' indicates wich column of the dataarray 
        // corresponds to the grid's column
        $this->setColumnAttr(2, 'index', null);
        $this->setColumnAttr(3, 'index', 2);

        // indicates if the filter field should appear or not
        if ( ( $f = $state->get('filtro') ) === NULL )
        {
            $f = TRUE;
        }

        // set the grid's filter field
        $this->setFilter($f);
        $state->set('filtro', $f);

        // wich column is the grid index?
        $this->setIndex(0);

        // box title
        $this->setTitle(_M('Grid of students', $module));

        // Method to be executed before each line renderization
        $this->setRowMethod('grdList3', 'MyRowMethod');

        // create grid actions
        $href_edit = $MIOLO->getActionURL('example', 'main:aluno', '%0%', array( 'event' => 'btnEdit:click' ));
        $href_dele = $MIOLO->getActionURL('example', 'main:aluno', '%0%', array( 'event' => 'btnDelete:click' ));

        $this->addActionUpdate($href_edit);
        $this->addActionDelete($href_dele);
        $this->addActionText('alt', _M('Edit'), $href_edit);
        $this->addActionSelect();
        $this->setActionDefault($href_edit);

        // set the controls in the grid
        $this->setControls($ctlArray);

        // alternate row background colors?
        $this->setAlternate(true);

        // define header links
        $this->headerLink('new', _M('HeaderLink - New Record', $module), $MIOLO->getActionURL('example', 'main:aluno', '', array( 'event' => 'btnInsert:click' )));

        if ( $item == 'detalhes' )
        {
            $this->setColumnAttr(0, 'visible', true);
            $this->headerLink('det', _M('HeaderLink - Summary', $module), $MIOLO->getActionURL($module, $self, 'sumario'));
        }
        else
        {
            $this->setColumnAttr(0, 'visible', false);
            $this->headerLink('det', _M('HeaderLink - Details', $module), $MIOLO->getActionURL($module, $self, 'detalhes'));
        }

        // call the eventHandler method
        $this->eventHandler();
    }

    /**
     * Executed before line renderization. 
     * Method to be executed before each line renderization.
     * @param $i - line index
     * @param $row - array containing the row data - initial index = 0
     * @param $actions - array containing the action's data
     * @param $columns - array containing the column's data
     */
    public function myRowMethod($i, $row, $actions, $columns)
    {
        // example:
        // if there exist 'A' in the curso, disable delete action, enable edit e renderiza in red
        // else enable the delete action, disable edit and renderiza in blue
        if ( $row[2]{0} == 'A' )
        {
            $actions[1]->enabled = false;
            $actions[2]->enabled = true;
            $columns[3]->control[$i]->_addStyle('color', 'red');
        }
        else
        {
            $actions[1]->enabled = true;
            $actions[2]->enabled = false;
            $columns[3]->control[$i]->addStyle('color', 'blue');
        }

        // example:
        // fill the 2nd column with the initial letter of the 1st column's value
        $columns[2]->control[$i]->setValue(substr($row[1], 0, 1));
        // example:
        // change the 5nd column action, putting the column value
        $action = $columns[5]->control[$i]->action;
        $n = count($row);
        for ( $r = 0; $r < $n; $r++ )
        {
            $action = str_replace("%$r%", trim($row[$r]), $action);
        }
        $columns[5]->control[$i]->setAction($action);
    }

    /**
     * Filter button method
     */
    public function btnFilter_click()
    {
        global $state;

        $f = !$state->get('filtro');
        $this->setFilter($f);
        $state->set('filtro', $f);
    }

    /**
     *
     */
    public function btnShow_click()
    {
        if ( count($this->selecteds) )
        {
            $show[] = new MLabel("line => {position} - {value}");
            $select = $this->page->request('select' . $this->name);

            foreach ( $this->allSelecteds as $pageNum => $p )
            {
                $show[] = new MLabel(_M('Page @1', $module, $pageNum));

                foreach ( $p as $lineNum )
                {
                    $pos = ( ( $pageNum - 1 ) * $this->getPageLength() ) + $lineNum;
                    $value = $this->alunos[$pos][1];
                    $show[] = new MLabel("$lineNum => $pos - {$value}");
                }
            }
        }
        else
        {
            $show[] = new MLabel(_M('No line selected', $module).'<br/>');
        }

        $show[] = new MSpacer('20px');
        $container = new MVContainer('container', $show);
        $this->manager->getTheme()->insertContent($container);
    }

    public function btnAlternate_click()
    {
        global $state;

        $f = !$state->get('alternate');
        $this->setAlternate($f);
        $state->set('alternate', $f);
    }

}
?>