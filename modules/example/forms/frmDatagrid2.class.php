<?php

class frmDataGrid2 extends MForm
{
    public $listURL;
    public $objSistema;
    public $objGrupo;
    public $objTransacao;
    public $perms;

    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();
        $context = $MIOLO->getContext();

        $this->listURL = $MIOLO->getActionURL($module, $action, '', array( 'event' => 'btnList:click' ));
        $this->objGrupo = $MIOLO->getBusiness('admin', 'grupoacesso');
        $this->objTransacao = $MIOLO->getBusiness('admin', 'transacao');
        $this->objSistema = $MIOLO->getBusiness('admin', 'sistema');
        $this->form('DataGrid2 - ' . _M('Transactions', $module));
        $this->setHelp($this->listURL);
        $this->showReturn(true);
        $this->showReset(true);
        $this->eventHandler();
    }

    public function createFields()
    {
        $action = MIOLO::getCurrentAction();

        $query = $this->objGrupo->listAll();
        $grupos = $query->chunkResult();
        $this->perms = array(
            "1" => "SELECT",
            "2" => "INSERT",
            "4" => "DELETE",
            "8" => "UPDATE",
            "15" => "EXECUTE",
            "31" => "SYSTEM"
        );
        $module = MIOLO::getCurrentModule();
        $mt_field = array(
            array( 'mtgrupos', _M('Groups', $module), '', $grupos ),
            array( 'mtperms', _M('Permissions', $module), '', $this->perms )
        );
        $fields = array(
            new HiddenField('key', ''),
            new TextLabel('txtIdTrans', '', 'Id'),
            new LookupTextField('lkpTransacao', '', _M('Transaction', $module), 20),
            new Selection('selIdSistema', '', _M('System', $module)),
            new MultiTextField2('mtfGrupos', null, _M('Access', $module), $mt_field, 180, true, 'horizontal')
        );
        $this->setFields($fields);
        $this->setFieldAttr('txtIdTrans', 'visible', false);
        $this->setFieldAttr('lkpTransacao', 'module', 'admin');
        $this->setFieldAttr('lkpTransacao', 'item', 'transacao');
        $this->setFieldAttr('lkpTransacao', 'event', 'btnEdit:click');
        $objQuery = $this->objSistema->listAll();
        $this->setFieldAttr('selIdSistema', 'options', $objQuery->result);
        $buttons = array(
            new FormButton('btnPost', _M('Send')),
            new FormButton('btnNew', _M('New', $module), $action),
            new FormButton('btnDelete', _M('Remove', $module)),
            new FormButton('btnList', _M('Relation', $module), $this->listURL),
        );
        $this->setButtons($buttons);
        $this->addValidator(new RequiredValidator('lkpTransacao'));
    }

    public function getData()  // nome dos formfields != business fields
    {
        $data = new FormData();
        $data->idtrans = $this->getFieldValue('key');
        $data->transacao = $this->getFieldValue('lkpTransacao');
        $data->idsistema = $this->getFieldValue('selIdSistema');
        $data->grupos = $this->mtfGrupos->getCodeValue();
        return $data;
    }

    public function setData($data)
    {
        $this->setFieldValue('key', $data->idtrans);
        $this->setFieldValue('txtIdTrans', $data->idtrans);
        $this->setFieldValue('lkpTransacao', $data->transaction);
        $this->setFieldValue('selIdSistema', $data->systemId);
        $this->mtfGrupos->setCodeValue($data->grupos);
    }

    public function btnPost_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $transacao = $MIOLO->getBusiness($module, 'transacao');
        if ( $transacao )
        {
            $transacao->setData($this->getData());
            $is_update = ($this->getFieldValue('key') != '');
            if ( $is_update )
            {
                $ok = $transacao->update();
            }
            else
            {
                $ok = $transacao->insert();
            }
            if ( $ok )
            {
                $MIOLO->information(_M('Transactions successfully updated', $module), $this->listURL);
                return true;
            }
            $this->addError($transacao->getErrors());
        }
    }

    public function myRowMethod($row, $actions, $columns, $query)
    {

        if ( substr($row[$query->getColumnNumber('SISTEMA')], 0, 1) == 'S' )
        {
            $actions[1]->enabled = false;
            $actions[2]->enabled = true;
            $columns['sistema']->control->setAttribute("style", "{color=red;font-size=14px;}");
        }
        else
        {
            $actions[1]->enabled = true;
            $actions[2]->enabled = false;
            $columns['sistema']->control->setAttribute("style", "{}");
        }
        if ( substr($row[$query->getColumnNumber('transacao')], 0, 1) == 'C' )
        {
            $columns['transacao']->control->setClass('gridAttention');
        }
    }

    public function btnList_click()
    {
        global $theme, $item;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        $btnArray = array(
            new FormButton('btnExemplo', _M('Example button', $module)),
            new FormButton('btnArray1', _M('Array button - List selected', $module)),
            new FormButton('btnArray2', _M('Array button', $module))
        );
        $selPerms = new Selection('selPerms', _M('Choose a permission', $module), _M('Permissions', $module), $this->perms);
        $selSistema = $this->selIdSistema;
        $selSistema->name = 'selSistema';
        $href_trans = $MIOLO->getActionURL($module, $action, '#0#', array( 'event' => 'btnEdit:click' ));
        $columns = array(
            new DataGridColumn('idtrans', 'Id', 'right', true, '', false),
            new DataGridHyperlink('transacao', _M('Transaction', $module) .' - OrderBy', $href_trans, '70%', true, null, true),
            new DataGridColumn('sistema', _M('System', $module), 'left', true, '30%', true, null, false, true),
            new DataGridControl($selSistema, 'idsistema', 'ControlColumn')
        );
        $href_datagrid = $MIOLO->getActionURL($module, $action, '', array( 'event' => 'btnList_click' ));
        $query = $this->objTransacao->listAll();

        $datagrid = new DataGrid2($query, $columns, $href_datagrid, 15);

        $datagrid->addFilterSelection('idsistema', _M('Sistema', $module), $this->selIdSistema->options);
        $datagrid->addFilterText('transacao', _M('Transaction', $module), '');
        $datagrid->setFilter(true);

        $datagrid->setTitle(_M('Transaction Relation', $module));
//        $datagrid->setLinkType('linkbutton');
        $datagrid->setRowMethod('frmDatagrid2', 'MyRowMethod');
        $href_edit = $MIOLO->getActionURL($module, $action, '%0%', array( 'event' => 'btnEdit:click' ));
        $href_dele = $MIOLO->getActionURL($module, $action, '%0%', array( 'event' => 'btnDelete:click' ));
        $datagrid->addActionUpdate($href_edit);
        $datagrid->addActionDelete($href_dele);
        $datagrid->addActionText('alt', _M('Edit', $module), $href_edit, 0);
        $datagrid->addActionSelect();
        $datagrid->setButtons($btnArray);
        $datagrid->setControls($selPerms);
        $datagrid->headerLink('new', 'HeaderLink - ' . _M('New', $module),
                $MIOLO->getActionURL($module, $action, '', array( 'event' => 'btnInsert:click' )));
        if ( $item == 'detalhes' )
        {
            $datagrid->setColumnAttr('idtrans', 'visible', true);
            $datagrid->headerLink('det', 'HeaderLink - ' . _M('Summary', $module),
                    $MIOLO->getActionURL($module, $action, '', array( 'event' => 'btnList:click' )));
        }
        else
        {
            $datagrid->setColumnAttr('idtrans', 'visible', false);
            $datagrid->headerLink('det', 'HeaderLink - ' . _M('Details', $module),
                    $MIOLO->getActionURL($module, $action, 'detalhes', array( 'event' => 'btnList:click' )));
        }
        $theme->insertContent($datagrid);
    }

    public function btnEdit_click($key='')
    {
        global $item;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $item = $key ? $key : $item;
        $transaction = $MIOLO->getBusiness('admin', 'transacao');
        if ( $transaction )
        {
            $this->setData($transaction->getById($item));
            $this->setFieldAttr('lkpTransacao', 'readonly', true);
            $this->setButtonAttr('btnNew', 'label', _M('Cancel', $module));
            $this->addError($transaction->getErrors());
        }
    }

    public function btnInsert_click()
    {
        
    }

    public function btnDelete_click()
    {
        global $item;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        $idtrans = isset($item) ? $item : $this->getFieldValue('key'); // $item, se get  - $this->key, se post
        $MIOLO->assert(isset($idtrans), _M('Transaction not informed!', $module), $action);
        $transacao = $MIOLO->getBusiness('admin', 'transacao');
        $transacao->getById($idtrans);
        $conf = $this->getFormValue('conf');
        if ( $conf == 'sim' )
        {
            if ( $transacao->delete() )
            {
                $MIOLO->prompt(Prompt::information(_M('Register removed successfully.', $module), $this->listURL));
            }
            else
            {
                $MIOLO->prompt(Prompt::information($transacao->getErrors(), $action));
            }
        }
        elseif ( $conf == 'nao' )
        {
            $MIOLO->prompt(Prompt::information(_M('Removing cancelled.', $module), $this->listURL));
        }
        else
        {
            $action_sim = $MIOLO->getActionURL($module, $action, $idtrans, array( 'event' => 'btnDelete:click', 'conf' => 'sim' ));
            $action_nao = $MIOLO->getActionURL($module, $action, $idtrans, array( 'event' => 'btnDelete:click', 'conf' => 'nao' ));
            $MIOLO->prompt(Prompt::question(_M('Confirm removing transaction', $module) . " [$transacao->transaction]?",
                            $action_sim, $action_nao));
        }
    }

    public function btnExemplo_click()
    {
        global $theme;
        $module = MIOLO::getCurrentModule();
        $lnkBack = new Hyperlink('', _M('Example button - click to return', $module), $this->listURL);
        $theme->insertContent($lnkBack);
    }

    public function btnArray1_click()
    {
        global $theme;
        $module = MIOLO::getCurrentModule();
        $lnkBack = new Hyperlink('', _M('List selected - click to return', $module), $this->listURL);
        $texto = '<br>';
        $selecionados = Form::getFormValue('selectRow');
        foreach ( $selecionados as $k => $v )
        {
            $this->objTransacao->getById($v);
            $texto .= $v . " - " . $this->objTransacao->transaction . "<br>";
        }
        if ( $texto )
            $txtShow = new Text('txtShow', $texto);
        $show = array( $lnkBack, $txtShow );
        $theme->insertContent($show);
    }

    public function btnArray2_click()
    {
        global $theme;
        $module = MIOLO::getCurrentModule();
        $lnkBack = new Hyperlink('', _M('Click to return', $module), $this->listURL);
        $theme->insertContent($lnkBack);
    }
}
