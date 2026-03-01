<?php

class ViewSource extends MLink
{
    public function __construct($file)
    {
        parent::__construct('', _M('View Source', MIOLO::getCurrentModule()));
        $urlWindow = $this->manager->getActionURL('example','doc', $file);
        $win = new MWindow('viewSourceWindow',array('url'=>$urlWindow));
        $this->setHRef($win->getLink(true, false));
        
    }
}
