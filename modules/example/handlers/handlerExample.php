<?php

class HandlerExample extends MHandler
{
    public function init()
    {
        parent::init();

        $this->manager->uses('classes/controlForm.php', MIOLO::getCurrentModule());
        $this->manager->trace('HandlerExample:init');
    }

}
