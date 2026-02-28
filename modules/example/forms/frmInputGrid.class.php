<?php
// access to the view source class
$MIOLO->import('modules::example::controls::viewsource');

class frmInputGrid extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('@1 Example', MIOLO::getCurrentModule(), 'InputGrid'));

        // verify if the form was submitted
        if ( $this->page->isPostBack() )
        {
            // call events (in this case btnPost_click)
            $this->eventHandler();
        }

        // add separator
        $this->addField(new MSeparator);
        // add link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    /**
     * this method is automatically called by MIOLO
     * and is intended to create the form fields
     */
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $this->addField(new MLabel(_M('This is an example of MInputGrid, a matrix of MTextField controls which are accessed by a pair of indexes (row, column)'), 'red', true));

        // creates a new Input Grid
        $ig = new MInputGrid('igExample', _M('Label', $module), 7);

        // adds column to the grid
        $ig->addColumn(_M('Column @1', $module, '1'), _M('Column Title @1', $module, '1'), 10, _M('Default value', $module).' 1');
        $ig->addColumn(_M('Column @1', $module, '2'), _M('Column Title @1', $module, '2'), 20, _M('Default value', $module).' 2');
        $ig->addColumn(_M('Column @1', $module, '3'), _M('Column Title @1', $module, '3'), 20, _M('Default value', $module).' 3');
        $ig->addColumn(_M('Column @1', $module, '4'), _M('Column Title @1', $module, '4'), 15, _M('Default value', $module).' 4');
        // change the value of a specific row, col
        $ig->setFieldValue(3, 4, '>>>' . _M('Changed', $module) . '<<<');
        $ig->setFieldValue(5, 3, '>>>' . _M('New value', $module) . '<<<');
        $ig->setFieldValue(6, 2, '>>>' . _M('Changed', $module) . '<<<');

        // adds the fields to the form
        $this->addField($ig);
        // define a button
        $buttons = array( new MButton('btnPost', _M('Send')) );
        // add the button to the form
        $this->setButtons($buttons);
    }

    /**
     * this method handles the button click (submit) and
     * is called by the constructor (eventHandler)
     */
    protected function btnPost_click()
    {
        // add text
        $this->addField(new MText('txt1', _M('Getting all values'), '', true));
        // set field to bold
//        $this->txt1->setBold();
        // gets the table's values
        $value = $this->getFieldValue('igExample');

        // create a new raw table with the submitted values
        $t = new MTableRaw('', $value);
        $this->addField($t);

        // gets the value of a specific value of a row, column
        $value53 = $this->igExample->getFieldValue(5, 3);
        // show a text with the value
        $this->addField(new MText('txt2', _M('Getting specific value') . ': (' . _M('row') . ' 5, ' . _M('column') . ' 3) ' . $value53, '', true));
        // set text to bold
//        $this->txt2->setBold();
        // text field
        $this->addField(new MText('txt3', _M('Getting a specific row @1', MIOLO::getCurrentModule(), '(2)'), '', true));
        // set bold
//        $this->txt3->setBold();
        // show the values of row 3
        $row = $this->igExample->getRow(3);
        $tr = new MTableRaw('', $row);
        $this->addField($tr);
    }
}
?>
