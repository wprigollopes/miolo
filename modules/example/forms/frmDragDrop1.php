<?php

class frmDragDrop1 extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Drag&Drop', $module) . ' - ' . _M('Sample', $module) . ' 1');
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $ui = $this->manager->getUI();
        $this->page->addStyleCode(".dropHover {background-color: #EEE}");

        $drag1 = new MDiv('drag1', _M('Sample text', $module));
        $drag1->width = '100px';
        $drag1->backgroundColor = 'red';
        $drag1->margin = '10px';

        $drag2 = new MImage('drag2', '', $ui->getImage('', 'button_refresh.png'));
        $drag2->margin = '10px';

        $drag3 = new MDiv('drag3', _M('Another text', $module));
        $drag3->width = '100px';
        $drag3->backgroundColor = 'blue';

        $source = new MBaseGroup('dragBox', _M('Drag from here', $module), array( $drag1, $drag2, $drag3 ));
        $labelSource = new MLabel(_M('Drag from here', $module));
        $boxSource = new MDiv('boxSource', array( $labelSource, $source ));

        $drop1 = new MDiv('dropBox1');
        $drop1->border = "1px #000";
        $drop1->height = '100px';
        $drop1->width = '100px';
        $drop1->backgroundColor = '#ddd';
        $labelTarget1 = new MLabel(_M('Drop anything here', $module) . ' - 1');
        $boxTarget1 = new MDiv('boxTarget1', array( $labelTarget1, $drop1 ));

        $drop2 = new MDiv('dropBox2');
        $drop2->border = "1px #000";
        $drop2->height = '100px';
        $drop2->width = '100px';
        $drop2->backgroundColor = '#ddd';
        $labelTarget2 = new MLabel(_M('Drop only texts here', $module) . ' - 2');
        $boxTarget2 = new MDiv('boxTarget2', array( $labelTarget2, $drop2 ));

        $dd = new MDragDrop('dd1');
        $dd->addDraggable($source);
        $dd->addDropZone($drop1);
        $dd->addDropZone($drop2);

        $status = new MDiv('divText');

        $fields[] = $boxSource;
        $fields[] = new MHContainer('', array( $boxTarget1, new MDiv('', '&nbsp;'), $boxTarget2 ));
        $fields[] = $dd;
        $fields[] = array( new MLabel(_M('Status', $module)), $status );
        $this->setFields($fields);

        $buttons[] = new MButton(btnPost, _M('Send'));
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        $v = $this->dd1->getValue();
        foreach ( $v as $drag => $drop )
        {
            $dd[] = array( $drag, $drop );
        }
        $this->addField(new MTableRaw(_M('Results', $module), $dd, array( _M('Element dragged', $module), _M('& dropped on', $module) )));
    }
}
