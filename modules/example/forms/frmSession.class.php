<?php
$MIOLO->import('modules::example::controls::viewsource');

class frmSession extends MForm
{
    public function __construct() 
    {   
        parent::__construct(_M('Session usage example', MIOLO::getCurrentModule()));
    }
    
    public function createFields()
    {   
        global $counter;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $session = $MIOLO->session;

        $counter = $session->isRegistered("counter") ? $session->getValue("counter") : 0;
        $this->addField(new MTextLabel('cnt', $counter, _M('Global', $module)));
        $counter++;
        $session->register("counter");

        $char = (string)$session->isRegistered("sessionchar") ? $session->getValue("sessionchar") : 'A';
        $this->addField(new MTextLabel('ch', $char, _M('Local', $module)));
        $char .= 'A';
        $session->setValue('sessionchar',$char);

        $this->addField(new ViewSource(__FILE__));
    }
}
?>
