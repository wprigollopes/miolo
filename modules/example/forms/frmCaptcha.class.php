<?php

class frmCaptcha extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('@1 example', MIOLO::getCurrentModule(), 'Captcha'));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = new MCaptchaField('captcha', _M('Captcha', $module), _M('Type the characters you see in the image on your left', $module));
        $fields[] = new MButton('validate', _M('Validate', $module), $MIOLO->getUI()->getAjax('validateCaptcha'));

        $this->addFields($fields);

        //$valids[]= new MCaptchaValidator('captcha');
        //$this->setValidators( $valids );

        $this->setButtons(new MBackButton());
    }

    public function validateCaptcha( $args )
    {
        $module = MIOLO::getCurrentModule();
        if ( MCaptchaField::validate($args->captcha) )
        {
            new MMessage(_M('Captcha validated', $module), MMessage::TYPE_INFORMATION);
        }
        else
        {
            new MMessage(_M('Invalid characters', $module), MMessage::TYPE_WARNING);
        }
    }
}
