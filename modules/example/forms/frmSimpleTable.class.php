<?php

class frmSimpleTable extends MForm
{
    function __construct()
    {
        parent::__construct('MSimpleTable');
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $bgColor = array( '#EEEEEE', '#DDDDDD', '#CCCCCC', '#BBBBBB' );
        $table = new MSimpleTable('st', "cellspacing=0 cellpadding=0 align=center border=1 width=70%", 3, 4);
        for ( $i = 0; $i < 4; $i++ )
        {
            $table->setHead($i, "Head $i");
            $table->setHeadAttribute($i, 'align', 'center');
        }
        $footColor = array( 'blue', 'red', 'green', 'white' );
        for ( $i = 0; $i < 4; $i++ )
        {
            $label = new MLabel("Foot $i", $footColor[$i], true);
            $table->setFoot($i, $label);
            $table->setFootAttribute($i, 'align', 'center');
        }
        for ( $i = 0; $i < 3; $i++ )
        {
            for ( $j = 0; $j < 4; $j++ )
            {
                $table->setCell($i, $j, "[$i x $j]");
                $table->setCellAttribute($i, $j, 'bgcolor', $bgColor[($i + $j) % 4]);
                $table->setCellAttribute($i, $j, 'align', 'center');
            }
        }

        // A table with ColGroups

        $data[] = array( '1200', 'Unicode (BMP of ISO/IEC-10646)', '', '', 'X', 'X', '*' );
        $data[] = array( '1250', 'Windows 3.1 Eastern European', 'X', '', 'X', 'X', 'X' );
        $data[] = array( '1251', 'Windows 3.1 Cyrillic', 'X', '', 'X', 'X', 'X' );
        $data[] = array( '1252', 'Windows 3.1 US (ANSI)', 'X', '', 'X', 'X', 'X' );
        $data[] = array( '1253', 'Windows 3.1 Greek', 'X', '', 'X', 'X', 'X' );
        $data[] = array( '1254', 'Windows 3.1 Turkish', 'X', '', 'X', 'X', 'X' );
        $data[] = array( '1255', 'Hebrew', 'X', '', '', '', 'X' );
        $data[] = array( '1256', 'Arabic', 'X', '', '', '', 'X' );
        $data[] = array( '1257', 'Baltic', 'X', '', '', '', 'X' );
        $data[] = array( '1361', 'Korean (Johab)', 'X', '', '', '**', 'X' );
        $data[] = array( '437', 'MS-DOS United States', '', 'X', 'X', 'X', 'X' );
        $data[] = array( '708', 'Arabic (ASMO 708)', '', 'X', '', '', 'X' );
        $data[] = array( '709', 'Arabic (ASMO 449+, BCON V4)', '', 'X', '', '', 'X' );
        $data[] = array( '710', 'Arabic (Transparent Arabic)', '', 'X', '', '', 'X' );
        $data[] = array( '720', 'Arabic (Transparent ASMO)', '', 'X', '', '', 'X' );

        $head = array( 'Code-Page<BR>ID', 'Name', 'ACP', 'OEMCP', 'Windows<BR>NT 3.1', 'Windows<BR>NT 3.51', 'Windows<BR>95' );

        $tblColGroup = new MSimpleTable('st1', "align=center width=70% border=2 frame=hsides rules=groups");
        $tblColGroup->setCaption('CODE-PAGE SUPPORT IN MICROSOFT WINDOWS');
        $tblColGroup->setColGroup(0, "align=\"center\"");
        $tblColGroup->setColGroup(1, "align=\"left\"");
        $tblColGroup->setColGroup(2, "align=\"center\" span=\"2\"");
        $tblColGroup->setColGroup(3, "align=\"center\" span=\"3\"");

        foreach ( $head as $i => $h )
        {
            $tblColGroup->setHead($i, $head[$i]);
        }
        foreach ( $data as $r => $row )
        {
            foreach ( $row as $c => $s )
            {
                $tblColGroup->setCell($r, $c, $s);
            }
        }

        $fields = array(
            $table,
            new MSpacer('15px'),
            $tblColGroup,
            new MSpacer('15px'),
        );
        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
