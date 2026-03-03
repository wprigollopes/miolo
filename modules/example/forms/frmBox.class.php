<?php

class frmBox extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('MBox', MIOLO::getCurrentModule()));
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        /*
         * Creates a link to the controls
         *
         * Your miolo.conf or module.conf must have the configuration option
         * theme.options.close set to true
         *
         */
        $close = $MIOLO->getActionURL($module, 'main:controls');
        // creates a box container
        $box = new MBox(_M('@1 Example', $module, 'MBox'), $close);
        //adds a MLabel into the box
        $box->addControl(new MLabel(_M('A MLabel control with some content', $module)));

        // creates a box container
        $smallBox = new MBox(_M('Example MBox without close button', $module). ' - width: 400px');
        //adds a MLabel into the box
        $smallBox->addControl(new MLabel(_M('A @1 control', $module, 'MLabel')));
        $smallBox->width = '400px';

        // adds an image button to the box
        // gets an image located in the html/images dir
        // the first parameter indicates the module or miolo's html/images by default
        $img = $MIOLO->getUI()->getImage('', 'button_edit.png');
        $imgButton = new MImageButton('imgButton', _M('@1 label', $module, 'MImageButton'), $close, $img);
        $box->addControl($imgButton);

        $fields[] = $box;
        $fields[] = $smallBox;

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
