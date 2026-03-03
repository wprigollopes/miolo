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

/*
            if( ! $this->validator && method_exists($this->form,'getFieldValidator') )
            {
                $this->validator = $this->form->getFieldValidator($this->name);
            }
*/
            $r = isset($this->attrs->items['required']) || ($this->validator && $this->validator->type == 'required');

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
     * A classe filha adiciona um parâmetro extra que não é opcional. Nesse caso, é um pouco mais complicado de
     * resolver, pois não adianta adicionar um atributo opcional na classe pai, nem transformar em opcional um
     * dos parâmetros da classe filha.
     * Adicionei um parâmetro "obrigatório" na classe pai (aqui) e a princípio não ocorreu erro por falta
     * desse parâmetro em quem chama esse método.
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
?>
