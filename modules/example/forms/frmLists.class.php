<?php

class frmLists extends controlForm
{
    public function __construct()
    {
        parent::__construct(_M('List Controls', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $module = MIOLO::getCurrentModule();


        // MUnorderedList
        $cars = array( 'Volkswagen', 'Fiat', 'BMW', 'Toyota', 'Peugeot' );

        $fields[] = new MLabel('MUnorderedList ' . _M('of car brands', $module) . ':');
        $fields[] = new MUnorderedList('carsList', $cars);
        $fields[] = new MSeparator();


        // MOrderedList
        $motorcycles = array( 'Harley-Davidson', 'Honda', 'Suzuki', 'Yamaha' );

        $fields[] = new MLabel('MOrderedList ' . _M('of motorcycle brands', $module) . ':');
        $fields[] = new MOrderedList('motorcyclesList', $motorcycles);
        $fields[] = new MSeparator();


        // MSelection
        $numbers = array(
            _M('One', $module),
            _M('Two', $module),
            _M('Three', $module),
            _M('Four', $module),
            _M('Five', $module),
        );

        $fields[] = new MLabel('MSelection ' . _M('with', $module) . ' AutoSubmit:');
        $fields['id'] = new MSelection('selection0', '', _M('Number', $module), $numbers, FALSE, '', '', FALSE);
        $fields['id']->setAutoSubmit(true);
        $fields[] = new MSeparator();

        $numberOptions = array(
            new MOption('opt1', '1', _M('One', $module)),
            new MOption('opt2', '2', _M('Two', $module)),
            new MOption('opt3', '3', _M('Three', $module)),
            new MOption('opt4', '4', _M('Four', $module)),
            new MOption('opt5', '5', _M('Five', $module)),
        );

        $fields[] = new MLabel('MSelection ' . _M('with', $module) . ' MOption');
        $fields[] = new MSelection('selOptions', '', _M('Number', $module), $numberOptions);
        $fields[] = new MSeparator();

        $numbersAssoc = array(
            '1' => _M('One', $module),
            '2' => _M('Two', $module),
            '3' => _M('Three', $module),
            '4' => _M('Four', $module),
            '5' => _M('Five', $module),
        );
        $fields[] = new MLabel('MSelection ' . _M('with associative array', $module));
        $fields[] = new MSelection('selAssoc', '3', _M('Number', $module), $numbersAssoc);
        $fields[] = new MSeparator();

        $numbersArray = array(
            array( '1', _M('One', $module) ),
            array( '2', _M('Two', $module) ),
            array( '3', _M('Three', $module) ),
            array( '4', _M('Four', $module) ),
            array( '5', _M('Five', $module) ),
        );

        $fields[] = new MLabel('MSelection ' . _M('with array of array and with', $module) . ' ShowValues:');
        $fields[] = new MSelection('selArray', '', _M('Number', $module), $numbersArray, true);
        $fields[] = new MSeparator();


        // MMultiSelection
        $fields[] = new MLabel('MMultiSelection:');
        $fields[] = new MMultiSelection('selectionMulti', array( 'valueA', 'valueC' ), _M('Car brands', $module), $cars);
        $fields[] = new MSeparator();


        // MComboBox
        $colors = array(
            'FFFFFF' => _M('White', $module),
            '000000' => _M('Black', $module),
            'FF0000' => _M('Red', $module),
            'FFFF00' => _M('Yellow', $module),
            '0000FF' => _M('Blue', $module),
            '00FF00' => _M('Green', $module)
        );

        $fields[] = new MLabel("MComboBox:");
        $fields[] = new MComboBox('color', '', _M('Color', $module), $colors);
        $fields[] = new MSeparator();


        /*
         * MSelection with MOptionGroup
         * The array of the MOptionGroup must be a MOption array
         */
        $optGroup = array(
            new MOptionGroup('og', _M('Numbers', $module), $numberOptions)
        );

        $fields[] = new MLabel("MSelection with MOptionGroup: ");
        $fields[] = new MSelection('selectionGroup', '', _M('Label', $module), $optGroup);


        $this->addFields($fields);
    }
}

?>