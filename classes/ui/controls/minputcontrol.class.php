<?php

class MInputControl extends MFormControl
{
    public $autoPostBack;
    public $validator;

    public function __construct( $name, $value, $label = '', $color = '', $hint = '' )
    {
        parent::__construct( $name, $value, $label, $color, $hint );
        $this->autoPostBack = false;
    }

    public function setAutoPostBack( $value )
    {
        $this->autoPostBack = $value;
    }

    public function generateLabel()
    {
        $label = '';
        $this->showLabel = ( $this->formMode >= MFormControl::FORM_MODE_SHOW_ABOVE );

        if ( ( $this->showLabel ) && ( $this->label != '' ) )
        {
            $span  = new MSpan( '', $this->label, 'mCaption' );

            $r = $this->validator && $this->validator->type == 'required';

            if( $r && trim(MUtil::removeSpaceChars($this->label)) )
            {
                $span->setClass('mCaptionRequired');
            }

            $label = $this->generateLabelMode($this->painter->span( $span ));

        }

        return $label;
    }

    /*
     * Declaration of MActionControl::getOnClick($ref, $action, $attr) should be compatible with
     * MInputControl::getOnClick($action, $attr):
     * The child class adds an extra parameter that is not optional. In this case, it is a bit more complicated
     * to solve, because adding an optional attribute in the parent class does not help, nor does making one of
     * the child class parameters optional.
     * I added a "required" parameter in the parent class (here) and initially no error occurred due to the
     * lack of this parameter in whoever calls this method.
     */
    public function getOnClick($action, $attr, $optional)
    {
        if ( substr($action, 0,11) == 'javascript:' )
        {
            return $action;
        }
        else
        {
            return "javascript:miolo.doLink(this.{$attr},'{$this->formId}'); return false;";
        }
    }
}
