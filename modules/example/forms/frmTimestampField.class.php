<?php

class frmTimestampField extends controlForm
{
    public function __construct()
    {   
        parent::__construct(_M('Timestamp', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $time = new MTimeField('time', NULL, _M('Time', $module));
        $time->addEvent('change', $MIOLO->getUI()->getAjax('timestampChanged'));

        $fields[] = MMessage::getMessageContainer();
        $fields[] = $time;

        $fields['dateandtime'] = new MTimestampField('dateandtime', NULL, _M('Timestamp Example', $module));
        $fields['dateandtime']->addEvent('change', $MIOLO->getUI()->getAjax('timestampChanged'));

        $this->addFields($fields);
    }

    public function timestampChanged()
    {
        $data = $this->getData();
        new MMessage($data->time);
    }
}
?>
