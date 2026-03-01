<?php

$navbar->AddOption('List Controls', $module, action);

$panel = new MPanel('pnlDummy','List Controls');

$array = array( 'optA', 'optB', 'optC', 'optD', 'optE', 'optF' );

$opt1 = new MOption( 'MOption1', 'value1', 'label1', false,'id1' );
$opt2 = new MOption( 'MOption2', 'value2', 'label2', false,'id2' );
$opt3 = new MOption( 'MOption3', 'value3', 'label3', false,'id3' );
$opt4 = new MOption( 'MOption4', 'value4', 'label4', false,'id4' );

$aOptions = array($opt1, $opt2, $opt3, $opt4);
$aOptionsAssoc = array( 'valueA' => 'optA',
                        'valueB' => 'optB',
                        'valueC' => 'optC',
                        'valueD' => 'optD',
                        'valueE' => 'optE',
                        'valueF' => 'optF'
                       );

$aOptionsArray = array( array( 'valueA', 'optA' ),
                        array( 'valueB', 'optB' ),
                        array( 'valueC', 'optC' ),
                        array( 'valueD', 'optD' ),
                        array( 'valueE', 'optE' ),
                        array( 'valueF', 'optF' )
                       );

$ulList = new MUnorderedList( 'ulList', $aOptions );
$olList = new MOrderedList( 'olList', $aOptions );

$selection0 = new MSelection( 'selection0', 3, 'Label', $array );
$selection1 = new MSelection( 'selection1', 'valueC', 'Label', $aOptionsAssoc );
$selection2 = new MSelection( 'selection2', 'valueB', 'Label', $aOptionsAssoc, true );

$selection3 = new MSelection('selection3', 'valueB', 'Label', $aOptionsArray, NULL, _M('This one does not allow NULL value'));
$selection3->setAllowNullValue(false);

$selectionMulti = new MMultiSelection( 'selectionMulti', array('valueA','valueC'), 'Label', $aOptionsAssoc );

$combo = new MComboBox( 'combo','', 'Label', $aOptionsArray );

$optGroup = array( new MOptionGroup('og1','Option Group 1',$aOptions),
                   new MOptionGroup('og2','Option Group 2',$aOptions),
                   new MOptionGroup('og3','Option Group 3',$aOptions)
                  );

$selectionGroup = new MSelection('selectionGroup','','Label',$optGroup);
$controls = array( new MLabel( "Unordered List: " ), $ulList, new MSeparator(),
                   new MLabel( "Ordered List: " ), $olList, new MSeparator(),
                   new MLabel( "Selection with simple array: " ), $selection0, new MSeparator(),
                   new MLabel( "Selection with associative array: " ), $selection1, new MSeparator(),
                   new MLabel( "Selection with ShowValues: " ), $selection2, new MSeparator(),
                   new MLabel( "Selection with array of array: " ), $selection3, new MSeparator(),
                   new MLabel( "Multi Selection: " ), $selectionMulti, new MSeparator(),
                   new MLabel( "ComboBox: " ), $combo, new MSeparator(),
                   new MLabel( "Selection with OptionGroup: " ), $selectionGroup, new MSeparator(),
                  );

$panel->AddControl( $controls, '100%', 'clear' );

$theme->BreakContent();
$theme->InsertContent( $panel );

// create a link to view the source of this file
$src = new ViewSource( __FILE__ );
$theme->appendContent( $src );

?>
