<?php

class frmISR extends MForm
{
    public $box;

    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();
        parent::__construct(_M('@1 Sample', $module, 'ISR'));
        $this->defaultButton = false;
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $array_linkbutton0 = array(
            new MButton('btnRetrieve', _M('Simple retrieve', $module)),
            new MButton('btnIndex', _M('Insert index', $module)),
        );
        $array_linkbutton1 = array(
            new MTextField('edtKey', '', _M('Key', $module), 10),
            new MButton('btnDelete', _M('Delete record', $module)),
        );
        $array_linkbutton2 = array(
            new MTextField('edtKeyUpd', '', _M('Key', $module), 10),
            new MTextField('edtValueUpd', '', _M('Name', $module), 25),
            new MButton('btnUpdate', _M('Update record', $module)),
        );
        $fields = array(
            array(
                new MLabel(_M('Search in', $module) . 'CM_PESSOA:'),
                new MTextField('edtValue', '', '', 25),
                new MCheckBox('chkFono', 'fono', '', false, _M('Phonetics', $module)),
            ),
            $array_linkbutton0,
            $array_linkbutton1,
            $array_linkbutton2,
        );
        $this->setFields($fields);
    }

    public function btnRetrieve_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('common');
        $isr = $db->getISR();
        $value = $this->getFieldValue('edtValue');
        $fono = $this->chkFono->checked;
        $in = $isr->retrieve('cm_pessoa', 'nome', $value, $fono);
        $sql = new MSQL('idpessoa,nome,sexo', 'cm_pessoa', "idpessoa in ($in)", 'nome');
        $query = $db->getQuery($sql);
        $result = $query->result;
        $t = new MTableRaw(_M("Retrieve of '@1' in", $module, $value) . ' cm_pessoa::nome', $result);
        $this->addField($t);
    }

    public function btnIndex_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('common');
        $isr = $db->getISR();
        $pessoa = $MIOLO->getBusiness('common', 'pessoa', 1);
        $value = $this->getFieldValue('edtValue');
        $pessoa->nome = strtoupper($value);
        $pessoa->insert();
        $isr->indexer('cm_pessoa', 'nome', $pessoa->idpessoa, $pessoa->nome);
        $in = $isr->retrieve('cm_pessoa', 'nome', $value);
        $sql = new MSQL('idpessoa,nome,sexo', 'cm_pessoa', "idpessoa in ($in)", 'nome');
        $query = $db->getQuery($sql);
        $result = $query->result;
        $t = new MTableRaw(_M("Retrieve of '@1' in", $module, $value) . ' cm_pessoa::nome', $result);
        $this->addField($t);
    }

    public function btnUpdate_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('common');
        $isr = $db->getISR();
        $key = $this->getFieldValue('edtKeyUpd');
        $nome = $this->getFieldValue('edtValueUpd');
        $pessoa = $MIOLO->getBusiness('common', 'pessoa', $key);
        $pessoa->nome = strtoupper($nome);
        $pessoa->update();
        $isr->indexer('cm_pessoa', 'nome', $pessoa->idpessoa, $pessoa->nome);
        $in = $isr->retrieve('cm_pessoa', 'nome', $nome);
        $sql = new MSQL('idpessoa,nome,sexo', 'cm_pessoa', "idpessoa in ($in)", 'nome');
        $query = $db->getQuery($sql);
        $result = $query->result;
        $t = new MTableRaw(_M("Retrieve of '@1' in", $module, $value) . ' cm_pessoa::nome', $result);
        $this->addField($t);
    }

    public function btnDelete_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('common');
        $isr = $db->getISR();
        $pessoa = $MIOLO->getBusiness('common', 'pessoa', $key);
        $pessoa->delete();
        $key = $this->getFieldValue('edtKey');
        $isr->delete('cm_pessoa', $key);
    }
}
?>
