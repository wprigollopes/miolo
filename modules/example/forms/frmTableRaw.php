<?php

class frmTableRaw extends MForm
{
    public function __construct()
    {
        parent::__construct('MTableRaw');

        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $array = array(
            array( 'PHP', 'Tobias Gerken', '2002' ),
            array( 'PHP/XML', 'Juliano Nier', '2001' ),
            array( 'XML', 'Otavio Decio', '2001' ),
            array( 'HTML5: Up and Running', 'Mark Pilgrim', '2010' ),
            array( 'Javascript', 'Damiani', '2002' )
        );

        $colTitle = array(
            _M('Title', $module),
            _M('Author', $module),
            _M('Publication Date', $module)
        );

        $fields[] = new MLabel(_M('Table with titles on table and columns', $module));
        $fields[] = new MTableRaw(_M('Relation of published books', $module), $array, $colTitle);
        $fields[] = new MSeparator();

        $fields[] = new MLabel(_M('Table without titles', $module));
        $fields[] = new MTableRaw('', $array);
        $fields[] = new MSeparator();

        $fields[] = new MLabel(_M('Table with "alternate" option set as "true"', $module));
        $tblZebra = new MTableRaw('', $array);
        $tblZebra->setAlternate(true);
        $tblZebra->addAttribute('width', '80%');
        $fields[] = $tblZebra;

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }
}
