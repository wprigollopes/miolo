<?php
#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# @title
#   Autocompletion
#
# @description
#   Handler for autocompleting form field values.
#
# @see      miolo/ui/form.class,
#           miolo/common.js
#
# @topics   form, ui
#
# @created
#   2001/08/14
#
# @organisation
#   MIOLO - Miolo Development Team - UNIVATES Centro Universitario
#
# @legal
#   CopyLeft (L) 2001-2002 UNIVATES, Lajeado/RS - Brasil
#   Licensed under GPL (see COPYING.TXT or FSF at www.fsf.org for
#   further details)
#
# @contributors
#   Thomas Spriestersbach    [author] [ts@interact2000.com.br]
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
# 
# @maintainers
#   Thomas Spriestersbach    [author] [ts@interact2000.com.br]
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
#
# @history
#   See CVS history
#
# @id $Id: autocomplete.php,v 1.5 2003/06/17 20:04:58 vgartner Exp $
#---------------------------------------------------------------------
include_once '/etc/miolo/miolo.conf';

$module  = $_REQUEST['module'];
$action  = $_REQUEST['action'];
$item    = $_REQUEST['item'];
$related = $_REQUEST['related'];
$form    = $_REQUEST['form'];
$field   = $_REQUEST['field'];
$value   = $_REQUEST['value'];

$info = $MIOLO->AutoComplete($module,$item,$value);

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

echo "<html>\n";
echo "<body>\n";
echo "        Module=$module; Item=$item; Value=$value; Info=" . (is_array($info) ? implode(',',$info) : $info) . "<br>\n";
echo "<script language=\"JavaScript\">\n";
echo "    var form = top.frames['content'].document.$form;\n";

$i = 0;

foreach(explode(',',$related) as $r)
{
    echo "    form['frm_$r'].value = '{$info[$i++]}';\n";
}

echo "</script>\n";
echo "</body>\n";
echo "</html>";

?>
