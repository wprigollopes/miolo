<?php

class frmHtmlArea extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'HtmlArea'));
        $fields = array(
            new MHtmlArea('htmlarea', '', 'HtmlArea', 150, 10, 40),
        );
        $this->setFields($fields);
        $buttons = new MButton('btnPost', _M('Send'));
        $this->setButtons($buttons);
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function btnPost_click()
    {
        $value = $this->getFieldValue('htmlarea');
        $value = str_replace('<', '&lt;', $value);
        $value = str_replace('>', '&gt;', $value);
        $container = new MAreaContainer('container');
        $container->addElement($value, 'left');
        $this->setFields($container);
        $buttons[] = new MButton('btnBack', _M('Back'), $this->manager->getCurrentURL());
        $this->setButtons($buttons);
    }
}
?>
