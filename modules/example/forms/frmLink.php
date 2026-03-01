<?php

class frmLink extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Link Controls', MIOLO::getCurrentModule()));

        // creates a link to open the source code
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        // some link examples
        $link = new MLink('ilnkName', _M('A link with an onclick attribute', $module), $MIOLO->getCurrentURL());
        // adds an attribute to the link
        $link->setOnClick("javascript:alert('" . _M('Hello', $module) . "!');");

        $imgLink = new MImageLink('ilnkName1', _M('This link goes nowhere', $module), $MIOLO->getCurrentURL(), $MIOLO->getUI()->getImage('', 'button_edit.png'));
        $imgLinkLabel = new MImageLinkLabel('ilnkName2', _M('This link goes nowhere', $module), $MIOLO->getCurrentURL(), $MIOLO->getUI()->getImage('', 'button_edit.png'));

        $linkWindow = new MOpenWindow('lnkWindow', _M('Open Google at a new window', $module), 'http://www.google.com');

        
        $fields[] = new MLabel('MLink');
        $fields[] = $link;
        $fields[] = new MSpacer();

        $fields[] = new MLabel('MImageLink');
        $fields[] = $imgLink;
        $fields[] = new MSpacer();

        $fields[] = new MLabel('MImageLinkLabel');
        $fields[] = $imgLinkLabel;
        $fields[] = new MSpacer();

        $fields[] = new MLabel('MOpenWindow');
        $fields[] = $linkWindow;
        $fields[] = new MSpacer();

        $fields[] = new MLabel('MLink - '. _M('External link', $module));
        $fields[] = new MLink('externalLink', 'Solis', 'http://www.solis.coop.br', NULL, MLink::TARGET_BLANK, false);
        $fields[] = new MSpacer();

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
