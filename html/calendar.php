<?php
    
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

//Paths to search the MIOLO configuration file
$ConfigPaths[] = '/etc/miolo/miolo.conf'; //Default installation Path
$ConfigPaths[] = '../etc/miolo.conf'; //Always will be there

$FoundConfig = false;

//Let's search the file...
foreach ( $ConfigPaths as $cp )
{
    if ( file_exists($cp) )
    {
        include_once($cp);
        $FoundConfig = true;
        break;
    }
}

//Not Found? Fatal Error.
if ( $FoundConfig == false )
{
    echo '<br>';
    echo ' <table width="400" border="1" cellspacing="0" cellpadding="8" align="center" height="10%"> ';
    echo '   <tr>';
    echo '     <td style="color:#ffffff;font-weight:bold;background-color:#880000;text-align:center" colspan="2"> FATAL ERROR </td> ';
    echo '   </tr>';
    echo '   <tr>';
    echo '     <td class="errorText"><ul>';
    
    echo '       <strong>MIOLO\'s CONFIGURATION FILE NOT FOUND!</strong><br><br>';
    echo '       Looked in:<br>'; 
    
    foreach ( $ConfigPaths as $cp )
    {
        echo "<li>$cp </li>";
    }
    
    echo '       </ul>';
    echo '     </td>';
    echo '   </tr>';
    echo '   <tr>';
    echo '     <td> Please correct this information in '. $_SERVER['DOCUMENT_ROOT'] .$_SERVER['PHP_SELF'] ;
    echo '   </tr>';
    echo ' </table>';
    
    exit;
}

// Starts the whole thing (instanciates $MIOLO)
include $MIOLOCONF['home']['miolo'].'/startup.inc';

$MIOLO->Trace("HTTP_REFERER='" . getenv("HTTP_REFERER") . "'");

$module = 'common';
$MIOLO->Uses('ui/lookuptheme.class');
$MIOLO->Uses('ui/calendar.class');

$config_file = $MIOLOCONF['home']['etc'] . "/$module.conf";

if ( file_exists($config_file) )
{
    include_once($config_file);
}

$form  = MIOLO::_Request('form');
$field = MIOLO::_Request('field');
$sep   = MIOLO::_Request('sep');

$handler = 'calendar.php?form='.urlencode($form).
                      '&field='.urlencode($field).
                      '&sep='  .urlencode($sep);

$date = MIOLO::_Request('date');

if ( ! $date ) 
{
    $date = MIOLO::_Request('dia').$sep.MIOLO::_Request('mes').$sep.MIOLO::_Request('ano');
}

if ( ! $date || $date == $sep.$sep ) 
{
    $date = date("d{$sep}m{$sep}Y");
}

$calendar = new Calendar($date);
$calendar->SetURL($handler);
$calendar->form_name  = $form;
$calendar->field_name = $field;
$calendar->sep        = $sep;

$data = array();
$calendar->SetData($data);

$theme = new LookupTheme();
$start = '<div align=left>';
$end   = '</div>';
$content = array($start,$calendar,$end);
$theme->SetContent($content);

$MIOLO->SetTheme($theme);

$MIOLO->GenerateTheme();

?>
