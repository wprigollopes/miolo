<?php

class frmThemeBox extends MForm
{
    public function __construct()
    {
        parent::__construct('MThemeBox');
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $space = new MSpacer('15px');
        $content = new MLabel(_M('Content of @1', $module, 'MThemeBox'));
        $box1 = new MThemeBox(_M('MThemeBox Title @1', $module, '1') . ' - default', $content);
        $box2 = new MThemeBox(_M('MThemeBox Title @1', $module, '2') . ' - style: backgroundcolor', $content);
        $box2->backgroundColor = '#CCF';
        $box3 = new MThemeBox(_M('MThemeBox Title @1', $module, '3') . ' - style: width', $content);
        $box3->width = '50%';
        $box4 = new MThemeBox(_M('MThemeBox Title @1', $module, '4') . ' - style: width,float', $content);
        $box4->width = '50%';
        $box4->float = 'right';
        $fields = array( $box1, $space, $box2, $space, $box3, $space, $box4, $space );

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
