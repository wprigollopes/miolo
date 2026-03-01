<?php

class frmStepByStep2 extends MStepByStepForm
{
    public function __construct($steps=null)
    {
        $title = _M('Second Step', MIOLO::getCurrentModule());
        parent::__construct($title, $steps, 2, 3);
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        parent::createFields();

        $fields[] = new MTextField('motorcyleBrand', '', _M('Motorcycle Brand', $module));
        $dataStep1 = $this->getStepData(1);

        $fields[] = new MLabel(_M("The value of the field <b>@1</b> on step 1 is", $module, _M('Car Brand', $module)).":<b> {$dataStep1->carBrand} </b>");

        $this->addFields($fields);
    }
}
