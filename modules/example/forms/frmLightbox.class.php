<?php
$MIOLO->uses('ui/controls/mlightbox.class.php');

class frmLightbox extends MForm
{
    public function __construct()
    {
        parent::__construct('Lightbox');
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();
        $image = $this->manager->getUI()->getImageTheme($MIOLO->getTheme()->getId(), 'logo_miolo_big.png');

        $light1[] = new MLightBox('light1', _M('Click to see the image', $module), $image);
        $fields[] = new MBaseGroup('bg1', 'MLightBox', $light1);

        $icon = $this->manager->getUI()->getImageTheme($MIOLO->getTheme()->getId(), 'photoEnable.png');

        $light2[] = new MLightBoxIcon('light2', $image, $icon);
        $fields[] = new MBaseGroup('bg2', 'MLightBoxIcon', $light2);

        $light3[] = new MLightBoxImage('light3', $image, '50px');
        $fields[] = new MBaseGroup('bg3', 'MLightBoxImage', $light3);

        $light4[] = new MLightBoxButton('light4', _M('Click to see the image', $module), $image);
        $fields[] = new MBaseGroup('bg4', 'MLightBoxButton', $light4);

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
