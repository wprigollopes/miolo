<?php

class frmStepByStep3 extends MStepByStepForm
{
    public function __construct($steps=null)
    {
        $title = _M('Third Step', MIOLO::getCurrentModule());
        parent::__construct($title, $steps, 3);
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        parent::createFields();

        $data = $this->getStepData();

        $fields[] = MMessage::getMessageContainer();

        $info[] = new MLabel(_M('Car Brand', $module) . ': ' . $data->carBrand);
        $info[] = new MLabel(_M('Car Model', $module) . ': ' . $data->carModel);
        $info[] = new MLabel(_M('Motorcycle Brand', $module) . ': ' . $data->motorcyleBrand);

        $fields[] = new MBaseGroup('', _M('Information', $module), $info, 'vertical');

        $this->addFields($fields);
    }

    public function finalizeButton_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $data = $this->getStepData();

        $url = $MIOLO->getActionUrl($module, 'main:forms');
        $buttons[] = $this->closeButton($url);
        $this->finalizeStepByStep($buttons);

        new MMessageSuccess(_M('Step by step finalized'));
    }
}
?>
