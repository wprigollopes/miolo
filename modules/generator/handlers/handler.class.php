<?php
class HandlerGenerator extends Handler
{
    function init()
    {
        parent::init();
        $this->manager->trace(__METHOD__);
    }
}
?>
