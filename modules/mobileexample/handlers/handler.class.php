<?php

class HandlerMobileExample extends MHandler
{
    public function init()
    {
        parent::init();

        $this->manager->uses('classes/jCollapsible.class.php', 'mobileexample');
        $this->manager->page->onload("if ( typeof jQuery != 'undefined' ) $('.ui-page').trigger('create');");
    }
}
