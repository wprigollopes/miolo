<?php

class MBasePanel extends MContainer
{
    public $box;

    public function __construct($name = '', $caption = '', $controls = NULL, $close = '', $icon = '', $class = 'mPanelBody')
    {
        parent::__construct($name, $controls, 'horizontal');
        $this->box = new MBox($caption, $close, $icon);
        $this->setClass($class);
    }

    public function setTitle($title)
    {
        $this->box->setCaption($title);
    }

    public function addControl($control, $width = '', $float = 'left', $class = '')
    {
        if ( is_array($control) )
        {
            foreach ($control as $c)
            {
                $this->addControl($c, $width, $float);
            }
        }
        else
        {
            $cell = ($control instanceof MDiv) ? $control : new MDiv('',$control);
            $cell->setClass($class . ' ' . 'mPanelCellBox mPanelCell' . ucfirst($float));
            parent::addControl($cell);
        }
    }

    /*
     * Declaration of MBasePanel::insertControl($pos, $control, $width = '', $float = 'left', $class = '') should be
     * compatible with MContainerControl::insertControl($control, $pos = 0):
     * The child class reverses the order of the first two parameters and neither is optional. What is the best
     * solution in this case? I simply made $control optional, since it is placed in an MDiv which supports
     * null values.
     */
    public function insertControl($pos, $control = null, $width = '', $float = 'left', $class = '')
    {
        $cell = new MDiv('',$control, 'mPanelCellBox mPanelCell' . ucfirst($float) . ' ' . $class);
        parent::insertControl( $cell, $pos );
    }


    public function generate()
    {
        $body = new MDiv( $this->name, $this->getControls(), $this->getClass() );
        $this->box->setControls( array($body) );
        return $this->box->generate();
    }

}


class MPanel extends MBasePanel {}

?>
