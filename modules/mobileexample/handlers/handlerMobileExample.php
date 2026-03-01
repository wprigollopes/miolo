<?php

class HandlerMobileExample extends MHandler
{
    public function init()
    {
        parent::init();

        $this->manager->uses('classes/jCollapsible.php', 'mobileexample');
        $this->manager->page->onload("if ( typeof jQuery != 'undefined' ) $('.ui-page').trigger('create');");
    }
}
