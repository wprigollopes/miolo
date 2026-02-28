<?php

class frmTreemenu extends MForm
{
    public $query;

    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'MTreeMenu'));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $array = array(
            array( 0, 'BaseControl', '', 'root' ),
            array( 1, 'Control1', '', 0 ),
            array( 2, 'Control2', '', 0 ),
            array( 3, 'Control3', '', 0 ),
            array( 4, 'Control4', '', 0 ),
            array( 5, 'Control5', '', 0 ),
            array( 6, 'Control11', '', 1 ),
            array( 7, 'Control21', '', 2 ),
            array( 8, 'Control22', '', 2 ),
            array( 9, 'Control23', '', 2 ),
            array( 10, 'Control41', '', 4 ),
            array( 11, 'Control111', '', 6 ),
            array( 12, 'Control112', '', 6 ),
            array( 13, 'Control113', '', 6 ),
            array( 14, 'Control12', '', 1 ),
            array( 15, 'Control121', '', 14 ),
            array( 16, 'Control122', '', 14 ),
            array( 17, 'Control123', '', 14 )
        );

        $aluno = $this->manager->getBusiness('example', 'aluno');
        $criteria = $aluno->getCriteria();
        $criteria->addColumnAttribute('substr(nome,1,1)', 'first');
        $criteria->addColumnAttribute('nome');
        $criteria->addOrderAttribute('nome');
        $this->query = $criteria->retrieveAsQuery();


        // javascript event
        $fields[] = new MSeparator('<b>' . _M('Javascript Event - click on leaf node', $module) . '</b>');

        $fields['tree1'] = new MTreeMenu('tree1');
        $fields['tree1']->setItemsFromArray($array);
        $fields['tree1']->setSelectEvent("miolo.getElementById(\"leaf0\").value = item.description; miolo.getElementById(\"leaf0id\").value = item.id;");

        // Expand the tree to the given path
        $fields['tree1']->expandPath(array(0, 1, 6));
        
        $fields[] = array(
            new MTextField('leaf0', '', _M('Selected', $module)),
            new MTextField('leaf0id', '', 'Id'),
        );
        $fields[] = new MSeparator();


        // submit on click
        $fields[] = new MSeparator('<b>' . _M('Miolo Event - submit on click on leaf node', $module) . '</b>');

        $fields['tree2'] = new MTreeMenu('tree2');
        $fields['tree2']->setItemsFromArray($array);
        $fields['tree2']->setEventHandler('OnTree2Click');

        // Display the tree already expanded
        $fields['tree2']->setExpanded(true);

        $fields[] = new MDiv('divitemid', '');
        $fields[] = new MSeparator();

        // tree from query
        $fields[] = new MSeparator('<b>' . _M('Tree from Query', $module) . '</b>');
        $fields['tree3'] = new MTreeMenu('tree3', 2);
        $fields['tree3']->setItemsFromResult($this->query->result, _M('Students', $module), '0', '1');
        $fields['tree3']->setEventHandler();
        $fields[] = new MDiv('itemaluno', '');

        $this->setFields($fields);
    }

    public function onTree2Click($itemId)
    {
        $module = MIOLO::getCurrentModule();
        $field = new MText('itemid', "<b>" . _M('Tree', $module) . ": $name  -  " . _M('Item Id', $module) . ": $itemId</b>");
        $this->setResponse($field, 'divitemid');
    }

    public function tree3_click($itemId)
    {
        $module = MIOLO::getCurrentModule();
        $field = new MText('itemalunoid', "<b>" . _M('Item Id', $module) . ": $itemId</b>");
        $this->setResponse($field, 'itemaluno');
    }
}
?>
