<?php

class frmLabels extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Label Controls', MIOLO::getCurrentModule()));
        // creates a link to open the source code
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        // MPageComment generates HTML comments
        $fields[] = new MLabel("You'll see the MPageComment text as a comment in the generated HTML page");
        $fields[] = new MPageComment(_M("Invisible. You'll see this text as a comment in the generated html page.", $module));
        $fields[] = new MSeparator();

        // MSeparator can have a label
        $fields[] = new MSeparator(_M("A MSeparator with text", $module));

        // A simple MLabel
        $fields[] = new MLabel(_M('A text for a MLabel control', $module));
        $fields[] = new MSeparator();

        // A MText is like a MLabel but with ID. Also, this is a blue one.
        $fields[] = new MText('txtName', _M('A MText control with id', $module), 'blue');
        $fields[] = new MSeparator();

        // A green MTextHeader of level 1
        $fields[] = new MTextHeader('hName1', '1', _M('A MTextHeader H1', $module), 'green');
        $fields[] = new MSeparator();

        // A yellow MTextHeader of level 2
        $fields[] = new MTextHeader('hName2', '2', _M('A MTextHeader H2', $module), 'yellow');
        $fields[] = new MSeparator();

        // The MTextLabel is a MText with label. Simple as that. This one is red.
        $fields[] = new MTextLabel('txtLabel', _M('The MTextLabel text', $module), _M('The MTextLabel label', $module), 'red');

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
