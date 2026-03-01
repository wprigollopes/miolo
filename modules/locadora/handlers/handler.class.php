<?php

class HandlerLocadora extends Handler
{
    public function init()
    {
        parent::init();

        $this->manager->trace(' HandlerLocadora:init' );
        $this->manager->theme->setElement('module', new MModuleHeader('MIOLO','Locadora de Veículos') );
    }

}
