<?php

class frmImage extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Image Controls', MIOLO::getCurrentModule()));
        // creates a link to open the source code
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        // Gets an image located in the 'html/images' dir
        // The first parameter indicates the module or miolo's 'html/images' by default
        $image = $MIOLO->getUI()->getImage('', 'attention2.gif');

        // The label of the MImage component is used as the alt html attribute
        $img = new MImage('img', _M('Alternative text if the image is not found', $module), $image);
        $imgFormLabel = new MImageFormLabel('imgFormLabel', _M('An image description', $module), $image);

        $fields = array(
            new MLabel("MImage:"), $img, new MSeparator(),
            new MLabel("MImageFormLabel:"), $imgFormLabel
        );

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
