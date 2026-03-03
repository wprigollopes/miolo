<?php

class frmIFrame extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('@1 Sample', MIOLO::getCurrentModule(), 'IFrame'));
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $src = "http://www.miolo.org.br";

        $fields['frame'] = new MIFrame('frame', $src);
        $fields['frame']->addAttribute('frameborder', '1');
        $fields['frame']->addAttribute('width', '95%');
        $fields['frame']->addAttribute('height', '250px');

        $this->setFields($fields);

        $this->setButtons(new MBackButton());
    }
}
?>
