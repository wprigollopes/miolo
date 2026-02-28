<?php

class frmEditMask extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('@1 Sample', MIOLO::getCurrentModule(), 'EditMask'));
        $this->eventHandler();

        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = new MTextHeader('1', '', _M('Masks', $module));
        $fields[] = new MTextHeader('2', '', '# - [0-9] &nbsp;&nbsp;&nbsp;a - [a-z][0-9] &nbsp;&nbsp;&nbsp;A - [A-Z][0-9] &nbsp;&nbsp;&nbsp;l - [a-z] &nbsp;&nbsp;&nbsp;L - [A-Z]');
        $fields[] = new MSpacer('10px');

        $fields['mask1'] = new MTextField('mask1', '', _M('Mask @1', $module, '1'), 20, 'mask: #### - obrig');
        $fields['mask2'] = new MTextField('mask2', '', _M('Mask @1', $module, '2'), 20, 'mask: ##/##/####');
        $fields['mask3'] = new MTextField('mask3', '', _M('Mask @1', $module, '3'), 20, 'mask: (##)####-####');
        $fields['mask4'] = new MTextField('mask4', '12-34', _M('Mask @1', $module, '4'), 20, 'mask: #####-### - obrig');
        $fields['mask5'] = new MTextField('mask5', '', _M('Mask @1', $module, '5'), 20, 'mask: ###.###.###,##');
        $fields['mask6'] = new MTextField('mask6', '', _M('Mask @1', $module, '6'), 20, 'mask: aaa##.aa');
        $fields['mask7'] = new MTextField('mask7', '', _M('Mask @1', $module, '7'), 20, 'mask: AAA####-AA');
        $fields['mask8'] = new MTextField('mask8', '', _M('Mask @1', $module, '8'), 20, 'mask: aall##AALL');

        $fields['mask1']->addMask('####', false);
        $fields['mask2']->addMask('##/##/####');
        $fields['mask3']->addMask('(##)####-####');
        $fields['mask4']->addMask('#####-###', false);
        $fields['mask5']->addMask('###.###.###,##', true, _M('Invalid value', $module));
        $fields['mask6']->addMask('aaa##.aa');
        $fields['mask7']->addMask('AAA####-AA');
        $fields['mask8']->addMask('aall##AALL');

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
