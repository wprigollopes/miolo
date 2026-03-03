<?php

class frmEvents extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Events', MIOLO::getCurrentModule()));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $handlers = <<< HERE
window.sampleHandler = function(event)
{
    miolo.getElementById('divResponse').innerHTML = "Event(" + event.type + ") on target " + event.target.id + "; currentTarget= " + event.currentTarget.id;
}

window.changeColor = function(event)
{
    element = miolo.getElementById(event.target.id);
    color = dojo.style(element, "backgroundColor");
    if ((color == "rgb(255, 0, 0)") || (color == "red")) dojo.style(element, "backgroundColor", "blue");
    if ((color == "rgb(0, 0, 255)") || (color == "blue")) dojo.style(element, "backgroundColor", "red");
}

HERE;
        $this->page->addJsCode($handlers);

        $label = new MLabel(_M('Click here', $module), 'red', true);
        $label->addEvent('click', "window.sampleHandler(event);");

        $ajax = new MLabel(_M('Click to generate a AJAX event', $module), 'green', true);
        $ajax->addEvent('click', ":doAjaxEvent;10:20:30");

        $fields[] = new MSpacer('10px');
        $fields[] = new MLabel(_M('@1 Events', $module, 'DOM'), 'black', true);
        $fields[] = new MSeparator(_M('Click event on a div element', $module));

        $fields['div1'] = new MDiv('div1');
        $fields['div1']->width = '20px';
        $fields['div1']->height = '20px';
        $fields['div1']->backgroundColor = 'blue';
        $fields['div1']->addEvent('click', "window.sampleHandler(event);");

        $fields[] = new MSpacer('10px');
        $fields[] = new MSeparator(_M('Click event on an element', $module));
        $fields[] = $label;
        $fields[] = new MSpacer('10px');

        $fields['response'] = new MDiv('divResponse', _M('Events response area', $module));
        $fields['response']->addStyle('border', 'solid 1px #aaa');
        $fields['response']->addStyle('max-width', '200px');
        $fields['response']->addStyle('padding', '10px');
        $fields['response']->addStyle('text-align', 'center');

        $fields[] = new MSeparator(_M('Event on a div element when the mouse is over it', $module));
        $fields['div2'] = new MDiv('div2');
        $fields['div2']->width = '20px';
        $fields['div2']->height = '20px';
        $fields['div2']->backgroundColor = 'blue';
        $fields['div2']->addEvent('mouseover', "window.changeColor(event);");

        $fields[] = new MSpacer('20px');
        $fields[] = new MLabel(_M('@1 Events', $module, 'Ajax'), 'black', true);
        $fields[] = new MSeparator(_M('Ajax event on a div element', $module));
        $fields[] = array( $ajax, new MDiv('div3') );
        $fields[] = new MSpacer('10px');
        $fields[] = new MSeparator(_M('Ajax event on a button', $module));
        $btnAjax = new MButton('btnAjax', _M('Ajax Button', $module));
        $btnAjax->addEvent('click', ':doAjaxButton');
        $fields[] = array( $btnAjax, new MDiv('div4') );
        $fields[] = new MSpacer('10px');
        $fields[] = new MSeparator(_M('Ajax event on a MSelection', $module));
        $options = array(
            'A' => _M('Option @1', $module, 'A'),
            'B' => _M('Option @1', $module, 'B'),
            'C' => _M('Option @1', $module, 'C')
        );

        $selAjax = new MSelection('selAjax', '', _M('Ajax Selection', $module), $options);
        $selAjax->addEvent('change', ':doAjaxSelection');
        $fields[] = array( $selAjax, new MDiv('div5') );
        $fields[] = new MSpacer('10px');

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }

    public function doAjaxEvent($args)
    {
        $label = new MLabel($args);
        $this->manager->ajax->setResponseControls($label, 'div3');
    }

    public function doAjaxButton($args)
    {
        $label = new MLabel(_M('Ajax event from button click', $module));
        $this->manager->ajax->setResponseControls($label, 'div4');
    }

    public function doAjaxSelection($args)
    {
        $choice = $args->selAjax;
        $label = new MLabel(_M('Ajax event from selection change', $module) . ": $choice");
        $this->manager->ajax->setResponseControls($label, 'div5');
    }
}
?>
