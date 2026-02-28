<?php

class frmEventHandler extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'EventHandler'));

        // define array of options
        $options_sel = array(
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        );

        // define array of fields
        $fields = array( new MCheckBox('chkBox', 'value', 'CheckBox', true, 'Text'),
            new MRadioButton('radioButton', 'value', 'RadioButton', true, 'Text'),
            new MComboBox('combo', '', 'ComboBox', $options_sel, false, '', 10),
            new MSelection('select1', 'b', _M('Select (onchange event)', $module), $options_sel, false),
            new MText('select1Change', '')
        );

        // set the fields to the form
        $this->setFields($fields);

        // define array of buttons
        $buttons = array(
            new MButton('btnPost', _M('Send')),
            new MButton('btnEvent1', _M('Default Event')),
            new MButton('btnEvent2', _M('Attach Event Handler')),
            new MButton('btnEvent3', _M('Sharing Event')),
            new MButton('btnEvent4', _M('Javascript Event'), 'NONE'),
        );

        // add the buttons to the form
        $this->setButtons($buttons);

        // add the events to the buttons
        $this->btnEvent2->attachEventHandler('click', 'onBtnEvent2Click');

        // sharing event
        $this->btnEvent3->attachEventHandler('click', 'onBtnEvent2Click', 'parameter1');

        // javascript functions to handler events
        $code =
                <<< HERE
public function onBtnEvent4Click(event)
{
    e = new xEvent(event);
    alert(e.type + ' ' + e.target.id);
}

public function onSelect1Change(event)
{
    e = new xEvent(event);
    text = miolo.getElementById('select1Change');
    text.innerHTML = 'Valor selecionado: ' + e.target.options[e.target.selectedIndex].value;
}

HERE;
        $this->page->addJsCode($code);

        // a Javascript event (prefixed by 'on')
        $this->btnEvent4->attachEventHandler('onclick', 'onBtnEvent4Click');
        $this->select1->attachEventHandler('onchange', 'onSelect1Change');

        // call the eventHandler
        $this->eventHandler();
    }

    /**
     * Default post event handler
     */
    public function btnPost_click($sender = NULL)
    {
        // create a textfield
        $text = new MText('eventText', _M('@1 event raised', MIOLO::getCurrentModule(), 'btnPost'));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    /**
     * This is the default handler for the button btnEvent1
     */
    public function btnEvent1_click($sender = NULL)
    {
        $text = new MText('eventText', 'btnEvent1 Click (btnEvent1_click) Event Raised.');
        $sender->color = $text->color = 'red';
        $this->addField($text);
    }

    /**
     * If you want, you can associate event using the attachEventHandler
     */
    public function onBtnEvent1Click($sender = NULL)
    {
        //
        $text = new MText('eventText', _M('@1 click event raised', MIOLO::getCurrentModule(), 'btnPost'));
        $sender->color = $text->color = 'red';
        $this->addField($text);
    }

    /**
     * This method is shared by two buttons. The main difference is thar the btnEvent3
     * sends a parameter wich could be used to identify some data.
     * Also, the $sender object can be user to access some property.
     */
    public function onBtnEvent2Click($sender = NULL, $param = NULL)
    {
        if ( !$param )
        {
            $text = new MText('eventText', _M('@1 click event raised', MIOLO::getCurrentModule(), 'btnEvent2'));
            $sender->color = $text->color = 'green';
        }
        else
        {
            $text = new MText('eventText', _M('@1 click event raised with parameter @2', MIOLO::getCurrentModule(), 'btnEvent3', $param));
            $sender->color = $text->color = '#990000';
        }

        // add field to the form
        $this->addField($text);
    }
}
?>
