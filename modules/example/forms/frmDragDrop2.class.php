<?php

class frmDragDrop2 extends MForm
{
    private $correct = "a thing of beauty is a joy forever by John Keats";

    public function __construct()
    {
        parent::__construct(_M('Drag & Drop Sample', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $ui = $this->manager->getUI();

        $this->page->addStyleCode(".dropHover {background-color: #EEE}");
        $phrase = array( 'a', 'thing', 'a', 'Keats', 'by', 'joy', 'of', 'is', 'beauty', 'forever', 'John' );

        $dd = new MDragDrop('dd2');

        $i = 0;
        foreach ( $phrase as $p )
        {
            $d = new MDiv("{$p}_" . $i++, $p, 'word');
            $d->margin = '5px';
            $d->backgroundColor = '#ffA';
            $drag[] = $d;
        }

        $box = new MBaseGroup('dragBox', _M('Drag a word', $module), $drag);
        $dd->addDraggable($box);

        $dropOn = new MDiv('drop1', '&nbsp;');
//        $dropOn->height = '30px';
        $dropOn->width = '100%';

        $box1 = new MBaseGroup('dropBox', _M('Build the phrase, drop the words here in order', $module), array( $dropOn ));

        $dd->addDropZone($box1);

        $status = new MDiv('divText');

        $fields = array(
            $box,
            $box1,
            $dd,
            new MButton('btnTest', _M('Test', $module)),
            new MSpacer('10px')
        );
        $this->setFields($fields);
        $this->defaultButton = false;
    }

    public function btnTest_click()
    {
        $module = MIOLO::getCurrentModule();
        $in = $this->dd2->getValue();
        foreach ( $in as $word => $box )
        {
            $s .= substr($word, 0, strpos($word, '_')) . " ";
        }
        if ( trim($s) == $this->correct )
        {
            $a = new MLabel($s . " [" . _M('Congratulations', $module) . "!] ", 'blue', true);
        }
        else
        {
            $a = new MLabel($s . " [" . _M('Wrong! Try again!', $module) . "]", 'red', true);
        }
        $this->addField($a);
    }
}
?>
