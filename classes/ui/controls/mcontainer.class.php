<?php
class MContainer extends MContainerControl
{
    /* disposition of the content: horizontal|none or vertical */
    public $disposition;

//    public $separator;
//    public $spaceHeight; // spacing in pixels between fields in disposition=vertical
//    public $spaceWidth='&nbsp;&nbsp;'; //spacing in pixels between fields in disposition=horizontal

    /* How the labels are showed */
    public $formMode;
    
    /*  whether the label should be displayed alongside the fields
     *  This attribute was changed to private to force the use of the
     *  setShowLabel method. This change was necessary for cases where
     *  the programmer needs the content labels to be displayed
     */
    public $showLabel;

    /*   this property controls whether or not the labels of a
     *   container's contents are displayed. It is necessary to use
     *   the setShowChildLabel method to modify this property.
     */
    public $showChildLabel = true; //whether the labels of this container's contents will be displayed

    public function __construct($name = NULL, $controls = NULL, $disposition = 'none', $formMode = MFormControl::FORM_MODE_SHOW_ABOVE)
    {
        parent::__construct($name);
        $this->formMode = $formMode;
        $controls = (($controls != '') && is_array($controls)) ? $controls : array();
        $this->showLabel = false;
//        $this->spaceHeight = '3px';
//        $this->spaceWidth = '5px';
        $this->setControls($controls);
        $this->setDisposition($disposition);
    }

/*
    public function setSpaceHeight($value)
    {
        $this->spaceHeight = $value;
    }

    public function setSpaceWidth($value)
    {
        $this->spaceWidth = $value;
    }
*/

    public function setDisposition($disposition)
    {
        $this->disposition = ($disposition == 'none') ? 'horizontal' : $disposition;

/* the use of separator was replaced by css attributes - ely
        switch ($this->disposition)
            {
            case 'vertical':
                $div = new MSpacer($this->spaceHeight);

                break;

            case 'horizontal':
                $div = new MDiv('', $this->spaceWidth);

                break;

            default:
                $div = NULL;

                break;
            }

        $this->separator = $div;
*/
    }

    public function isShowLabel()
    {
        return $this->showLabel;
    }

    public function isShowChildLabel()
    {
        return $this->showChildLabel;
    }

    public function setShowChildLabel( $visible=true, $recursive=true )
    {
        $this->showChildLabel = $visible;
        $controls = $this->getControls();
        $this->setControls($controls,$recursive);
    }

    public function setShowLabel( $visible=true, $recursive=true )
    {
        $this->showLabel = $visible;

        if( $recursive )
        {
            $this->setShowChildLabel( $visible, $recursive );
        }
    }

    public function setControls($controls,$recursive=false)
    {
        $this->clearControls();

        foreach ( $controls as $c )
        {
            if ( $recursive && ($c instanceof MContainer) )
            {
                $c->setShowChildLabel($this->showChildLabel,true);
            }
            if( is_object($c) )  //added due to the error!
            {
                if ($c instanceof MFormControl)
                {
                    $c->showLabel = $this->showChildLabel;
                }
                $this->addControl($c);
            }
            /*else
            {
                trigger_error( _M('Trying to access a property on a non-object'), E_USER_WARNING);
            }*/
        }
    }

    public function generateInner()
    {
        $float = false;
        $t = array();

        $controls = $this->getControls();

        foreach ($controls as $control)
        {
            $c = clone $control;
            if ($c instanceof MInputControl)
            {
                $c->setAutoPostBack($this->autoPostBack || $c->autoPostBack);
            }
            if ( $c->showLabel )
            {
               $c->formMode = $this->formMode;
            }

            if ($this->disposition == 'horizontal')
            {
//old                $c->float = $this->separator->float = 'left';
//                $c->float = 'left';
//                $c->addBoxStyle('margin-right', $this->spaceWidth);

                if ( ($this->formMode == MFormControl::FORM_MODE_SHOW_SIDE) && !($c instanceof MContainer) )
                {
                    $c = new MFormContainer('', array( $c ));
                }
                else
                {
                    $c = ($c instanceof MDiv) ? $c : new MDiv('',$c);
                }

                $c->setClass('mContainerHorizontal');
                $float = true;
            }
            else
            {
                if ( $this->formMode == MFormControl::FORM_MODE_SHOW_SIDE )
                {
                    $form = new MForm();
                    $c = $form->generateLayoutField($c);
                }
                else
                {
                    $c = ($c instanceof MDiv) ? $c : new MDiv('',$c);
                    $c->setClass('mContainerVertical');
//                    $c->addBoxStyle('margin-bottom', $this->spaceHeight);
                }
            }

            $htmlPainter = new MHtmlPainter();
            $t[] = $htmlPainter->generateToString($c);
//            $t[] = $this->separator;
        }

        if ($float)
        {
            $t[] = new MSpacer();
        }

        $this->inner = $t;
//        $this->getBox()->setAttributes($this->getAttributes());
    }
}

class MVContainer extends MContainer
{
    public function __construct($name = NULL, $controls = NULL, $formMode = MFormControl::FORM_MODE_SHOW_ABOVE)
    {
        parent::__construct($name, $controls, 'vertical', $formMode);
    }
}

class MHContainer extends MContainer
{
    public function __construct($name = NULL, $controls = NULL)
    {
        parent::__construct($name, $controls, 'horizontal');
    }
}

class MFormContainer extends MContainer
{
    public function __construct($name=NULL, $controls=NULL)
    {
        parent::__construct($name, $controls, 'vertical', MFormControl::FORM_MODE_SHOW_SIDE);
    }
}

/**
 * Container class which creates the label of the first control aligned as any other input control.
 */
class MRowContainer extends MContainer
{
    /**
     * @var string Left padding between elements.
     */
    public $paddingLeft = '20px';

    /**
     * @return string Generate a label span with the label of the first control.
     */
    public function generateLabel()
    {
        $label = '';
        $controls = $this->getControls();
        $mainField = $controls[0];

        if ( strlen(trim($mainField->label)) )
        {
            $span = new MSpan('', $mainField->label . ':', 'label');

            /*
             * In principle, $this->attrs->items was never a valid value, the correct would be:
             *     $this->attrs->attrs->items['required']
             * or
             *     $this->attrs->getAttribute('required')
             * This means the first part of the conditional never returns true, making it unnecessary:
             * $this->attrs->items['required'] || ($mainField->validator && $mainField->validator->type == 'required')
             */
            if ($mainField->validator && $mainField->validator->type == 'required')
            {
                $span->setClass('mCaptionRequired');
            }

            $label = $this->painter->span($span);
        }

        return $label;
    }

    /**
     * @return string Generate a field span with all container fields, except the label of the first control.
     */
    public function generateFieldSpan()
    {
        $controls = $this->getControls();

        foreach ( $controls as $index => $control )
        {
            if ( strlen(trim($control->label)) && $index != 0 )
            {
                $span = new MSpan('', $control->label . ':', 'mText');
                $span->addStyle('padding-left', $this->paddingLeft);

                /*
                 * In principle, $this->attrs->items was never a valid value, the correct would be:
                 *     $this->attrs->attrs->items['required']
                 * or
                 *     $this->attrs->getAttribute('required')
                 * This means the first part of the conditional never returns true, making it unnecessary:
                 * $this->attrs->items['required'] || ($control->validator && $control->validator->type == 'required')
                 */
                if ($control->validator && $control->validator->type == 'required')
                {
                    $span->setClass('mCaptionRequired');
                }

                $label = $this->painter->span($span);

                $fields[] = $label;
            }

            $fields[] = $control;
        }

        $span = new MSpan(NULL, $fields, 'field');
        return $span->generate();
    }

    /**
     * @return string Generate component.
     */
    public function generate()
    {
        return $this->generateLabel() . $this->generateFieldSpan();
    }
}

?>