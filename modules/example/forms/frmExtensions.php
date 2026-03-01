<?php
MIOLO::import('extensions::spaw::spaw_control', 'M_SPAW_Wysiwyg');

class frmExtensions extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Extensions', MIOLO::getCurrentModule()));
    }

    public function createFields()
    {
        $fields = array(
            new M_SPAW_Wysiwyg(
                $control_name = 'spaweditor', // control's name
                $value = '', // initial value
                $lang = '', // language
                $mode = '', // toolbar mode
                $theme = '', // theme (skin)
                $width = '100%', // width
                $height = '300px', // height
                $css_stylesheet = '', // css stylesheet file for content
                $dropdown_data = ''           // data for dropdowns (style, font, etc.)
            )
        );
        $this->setFields($fields);

        $buttons[] = new FormButton('btnPost', _M('Send'));
        $this->setButtons($buttons);
    }
}
