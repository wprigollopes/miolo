<?php

class FrmThemeSkin extends Form
{
    public function __construct()
    {
        parent::__construct(_M('Theme Skin Sample', MIOLO::getCurrentModule()));

        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $options = array(
            'red' => 'ppg - blossom',
            'blue' => 'ppg - bubbles',
            'green' => 'ppg - buttercup'
        );
        $fields[] = new Selection('skin', '', _M('Choose', $module), $options);
        $this->setFields($fields);

        $buttons[] = new FormButton('btnPost', _M('Set skin', $module));
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $skin = $this->getFieldValue('skin') . '.tpl';
        $this->page->setStyles($skin);
    }
}
