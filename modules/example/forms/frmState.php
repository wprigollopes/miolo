<?php

class FrmState extends MForm
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();
        $this->form(_M('State', $module) . ' - ' . _M('only 1 page', $module));
        if ( $page->isPostBack() )
        {
            $choice = $this->getFieldValue('select1');
            $choiceopt = $this->getFieldAttr('select1', 'options', $choice);
            $this->setFieldAttr('select1', 'readonly', true);
            if ( $choice == 0 )
            {
                $choice2 = $this->getFieldValue('select1a');
                if ( $choice2 <> '' )
                {
                    $choice2opt = $this->getFieldAttr('select1a', 'options', $choice2);
                    $this->setFieldAttr('select1a', 'readonly', true);
                    $this->setFieldValue('finaltext', $choiceopt . " = " . $choice2opt);
                    $this->setFieldAttr('finaltext', 'readonly', true);
                    $this->setFieldAttr('finaltext', 'visible', true);
                }
                $this->setFieldAttr('select1a', 'visible', true);
            }
            else
            {
                $choice2 = $this->getFieldValue('select1b');
                if ( $choice2 <> '' )
                {
                    $choice2opt = $this->getFieldAttr('select1b', 'options', $choice2);
                    $this->setFieldAttr('select1b', 'readonly', true);
                    $this->setFieldValue('finaltext', $choiceopt . " = " . $choice2opt);
                    $this->setFieldAttr('finaltext', 'readonly', true);
                    $this->setFieldAttr('finaltext', 'visible', true);
                }
                $this->setFieldAttr('select1b', 'visible', true);
            }
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $options = array(
            _M('vowels', $module),
            _M('consonants', $module)
        );
        $fields[] = new Selection('select1', '', _M('Selection', $module) . ' 1', $options);
        $fields[] = new Selection('select1a', '', _M('Selection of vowels', $module), array( 'a', 'e', 'i', 'o', 'u' ));
        $fields[] = new Selection('select1b', '', _M('Selection of consonants', $module), array( 'b', 'c', 'd', 'f', 'g' ));
        $fields[] = new TextField('finaltext', 'value', _M('Final', $module));

        $this->setFields($fields);
        $this->setFieldAttr('select1', 'readonly', false);
        $this->setFieldAttr('select1a', 'readonly', false);
        $this->setFieldAttr('select1b', 'readonly', false);
        $this->setFieldAttr('select1a', 'visible', false);
        $this->setFieldAttr('select1b', 'visible', false);
        $this->setFieldAttr('finaltext', 'visible', false);
    }
}
