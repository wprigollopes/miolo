<?php

class MBaseLabel extends MOutputControl
{
    public function __construct( $name = NULL, $label = NULL, $color = '', $hint = '', $bold = false, $type = null )
    {
        parent::__construct( $name, $label, $color, $hint );
        $this->setBold($bold);
        $this->setType($type);
    }

    public function setBold( $value = true )
    {
        if ($value) $this->setClass('mLabelBold');
    }

    public function setType($type)
    {
        if (!is_null($type))
        {
            switch ($type)
            {
                case 'h3':
                    $this->setClass('h3');
                    break;
                case 'h4':
                    $this->setClass('h4');
                    break;
            }
        }
    }
}

class MLabel extends MBaseLabel
{
    public function __construct( $text = NULL, $color = '', $bold = false, $type = null )
    {
        parent::__construct( NULL, $text, $color, NULL, $bold, $type );
    }

    public function generateInner()
    {
        if ( $this->getClass() == '' )
        {
            $this->setClass( 'mLabel' );
        }
        $this->inner = ( trim($this->value) != '' ) ? $this->getRender( 'text' ) : '';
    }
}
