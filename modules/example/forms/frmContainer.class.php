<?php

class FrmContainer extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Container Example', MIOLO::getCurrentModule()));
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
        $this->defaultButton = false;
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        
        $controls[] = new MTextField('test1', NULL, _M('Test', $module));
        $controls[] = new MTextField('test2', NULL, _M('Test', $module));
        $controls[] = new MTextField('test3', NULL, _M('Test', $module));
        $fields[] = new MRowContainer(NULL, $controls);
        
        
        $validators[] = new MRequiredValidator('test1');
        $validators[] = new MRequiredValidator('test3');
        
        
        $container = new MAreaContainer('cntExample');

        $container->addElement(new MText('', _M('A MText at top area', $module)), 'top');
        $container->addElement(new MText('', _M('A MText at left area', $module)), 'left');
        $container->addElement(new MText('', _M('A MText at center area', $module)), 'center');
        $container->addElement(new MText('', _M('A MText at right area', $module)), 'right');
        $container->addElement(new MText('', _M('A MText at bottom area', $module)), 'bottom');
        $fields[] = $container;

        $this->setFields($fields);
        $this->setValidators($validators);
    }
}
?>
