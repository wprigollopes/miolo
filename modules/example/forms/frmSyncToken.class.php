<?php

class frmSyncToken extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Sync Token', $module));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array(
            new MLabel(_M('A Sync Token prevents from submitting the same form twice', $module), 'black', true),
            new MSpacer('10px'),
            new MLabel(_M('Click twice on post button', $module), 'black', true),
            new MSpacer('10px'),
        );
        $this->setFields($fields);

        $buttons = new MButton('btnPost', _M('Post', $module));
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        // Maybe a long time-running transaction...
        // Sync Token prevents the user execute twice (clicking twice on button) when the transaction is running yet    
        for ( $i = 0; $i < 10000; $i++ )
        {
            for ( $j = 0; $j < 1000; $j++ )
            {
                continue;
            }
        }
        $this->addField(new MLabel(_M('End of transaction', $module)));
    }
}
