<?php

class frmTabbedBaseGroup extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Tabbed Base Group', MIOLO::getCurrentModule()));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $tabBFields[] = new MTimestampField('dateandtime', NULL, _M('Timestamp Example', $module));
        $tabBFields[] = new MCalendarField('date', NULL, _M('Date', $module));

        $tabAFields[] = new MTextField('fieldA', NULL, _M('Field @1', $module, 'A'));
        $tabAFields[] = new MTextField('fieldB', NULL, _M('Field @1', $module, 'B'));
        $tabAFields[] = new MTextField('fieldC', NULL, _M('Field @1', $module, 'C'));
        $tabAFields[] = new MSelection('fieldD', NULL, _M('Field @1', $module, 'D'), NULL);

        $tabBFields[] = new MTextField('fieldE', NULL, _M('Field @1', $module, 'E'));
        $tabBFields[] = new MTextField('fieldF', NULL, _M('Field @1', $module, 'F'));

        $tabCFields[] = new MTextField('fieldX', NULL, _M('Field @1', $module, 'X'));
        $tabCFields[] = new MTextField('fieldY', NULL, _M('Field @1', $module, 'Y'));
        $tabCFields[] = new MTextField('fieldZ', NULL, _M('Field @1', $module, 'Z'));

        $data = array(array(1), array(2), array(3), array(4));
        $columns[] = new MGridColumn(_M('Number', $module));
        $grid = new MGrid($data, $columns, $MIOLO->getCurrentURL(), 2);
        $tabDFields[] = new MDiv(NULL, $grid);

        $testeTab = new MTabbedBaseGroup('testTab');
        $testeTab->createTab('tabA', _M('Tab @1', $module, 'A'), $tabAFields);
        $testeTab->createTab('tabB', _M('Tab @1', $module, 'B'), $tabBFields);
        $testeTab->createTab('tabC', _M('Tab @1', $module, 'C'), $tabCFields);
        $testeTab->createTab('tabD', _M('Tab @1', $module, 'D'), $tabDFields);
        $testeTab->createTab('tabAjax', _M('Tab @1', $module, 'Ajax'), NULL, 'tabAjax');
        $testeTab->getTab('tabC')->setDisabled();
        $testeTab->setInitialTab('tabAjax');

        $fields[] = $testeTab;

        $buttons[] = new MButton('disableTabC', _M('Disable Tab C', $module), $MIOLO->getUI()->getAjax('disableTabC'));
        $buttons[] = new MButton('enableTabC', _M('Enable Tab C', $module), $MIOLO->getUI()->getAjax('enableTabC'));
        $buttons[] = new MButton('enableTabAjax',  _M('Enable Tab Ajax', $module), $MIOLO->getUI()->getAjax('enableTabAjax'));
        $buttons[] = new MButton('removeTabB',  _M('Remove Tab B', $module), $MIOLO->getUI()->getAjax('removeTabB'));
        $buttons[] = new MButton('addTabExtra', _M('Add Tab', $module), $MIOLO->getUI()->getAjax('addTabExtra'));

        $fields[] = new MHContainer('buttonContainer', $buttons );

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }

    public function tabAjax( $args )
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = new MTextField('oneField', rand(), _M('One Field', $module));
        $fields[] = new MTextField('antoherField', NULL, _M('Another Field', $module));

        MTabbedBaseGroup::updateTab('tabAjax', $fields);
    }

    public function disableTabC()
    {
        MTabbedBaseGroup::disableTab('tabC', 'testTab');
    }

    public function enableTabC()
    {
        MTabbedBaseGroup::enableTab('tabC', 'testTab');
    }

    public function enableTabAjax()
    {
        MTabbedBaseGroup::enableTab('tabAjax', 'testTab');
    }

    public function removeTabB()
    {
        MTabbedBaseGroup::removeTab( 'tabB', 'testTab' );
    }

    public function addTabExtra()
    {
        $controls[] = new MTextField('test'.rand(), NULL, _M('Test', MIOLO::getCurrentModule()).rand(0,100));
        MTabbedBaseGroup::createStaticTab('testTab', 'tabExtra'.rand(),' Extra'.rand(0,100), $controls);
    }
}
