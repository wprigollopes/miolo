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

$module  = $_REQUEST['module'];
$action  = $_REQUEST['action'];
$item    = $_REQUEST['item'];
$related = $_REQUEST['related'];
$form    = $_REQUEST['form'];
$field   = $_REQUEST['field'];
$value   = $_REQUEST['value'];

$MIOLO->Trace("HTTP_REFERER='" . getenv("HTTP_REFERER") . "'");

$MIOLO->Uses('lookup.class');
$MIOLO->Uses('ui/lookuptheme.class');
$MIOLO->Uses('ui/listing.class');
$MIOLO->Uses('ui/form.class');
$MIOLO->Uses('ui/pagenavigator.class');

$ok = $MIOLO->Uses('/db/lookup.class',$module);

$MIOLO->Assert($ok,_M('File modules/@1/db/lookup.class not found.<br>'.
                      'This file must implement the Business@1Lookup Class '.
                      'containing the Lookup@2 and Autocomplete@2 functions.', 
                      'miolo',$module, $item));

$filter = Form::GetFormValue('filter');

$lookup = new Lookup();
$lookup->SetForm(new Form('Filter'));
$lookup->SetTitle('Lookup ' . $item);

eval("\$object = new Business{$module}Lookup();");
eval("\$object->Lookup$item(\$lookup);");

$filterForm = & $lookup->GetForm();

if ( ! count($filterForm->fields) )
{
    $filterForm->AddField(new TextField('Filtro','filtro',$filtro,30));
}

$db = $MIOLO->GetDatabase($lookup->GetDatabase());

$url = 'lookup.php' .
       '?module='   . urlencode($module) .
       '&item='     . urlencode($item) .
       '&related='  . urlencode($related) .
       '&value='    . urlencode($value) .
       '&form='     . urlencode($form)  .
       '&field='    . urlencode($field);
       
$filterFields = $lookup->GetFilterFields();

$filterForm->SetAction($url);

if ( $filterFields )
{
    foreach( $filterFields as $f )
    {
        $url .= "&$f=" . urlencode(Form::GetFormValue($f,$HTTP_GET_VARS[$f]));
    }
}
else
{
    $url .= '&filter='   . urlencode($filter);
}

$pn = new PageNavigator($lookup->GetPageLength(), $url);

if ( Form::IsSubmitted() )
{
    $pn->SetCurrentPage(0);
}

if ($lookup->module && $lookup->business && $lookup->method)
{
    $business = $MIOLO->GetBusiness($lookup->module, $lookup->business);
    eval("\$data = \$business->{$lookup->method}(\$pn->GetQueryRange(),\$lookup->data);");
}
else
{
    $data = $db->QueryRange($lookup->GetQuery(), $pn->GetQueryRange());
}

$listing = new Listing();
$listing->SetTitle($lookup->listingTitle);
$listing->SetLabels($lookup->labels);
$listing->SetData($data);

# build argument list for MIOLO_Deliver function
for ( $i=0; $i<count($data[0]); $i++ )
{
    if ( $i )
    {
        $args .= ',';
    }
    
    $args .= "&quot;#$i#&quot;";
}

$listing->AddActionIcon('ID','select.gif',"javascript:MIOLO_Deliver($args);");

$listing->SetFooter($pn);
$listing->AddError($db->GetErrors());

# define global JS variable containing the lookup context
$name_lookup = urlencode('lookup_' . $form . '_' . $field);
$script = " <script language \"Javascript\">\n" .
          "   var lookup = eval('window.opener.$name_lookup');\n" .
          " </script>\n";

$theme = new LookupTheme();

$content = array($lookup->GetForm(),$listing);

$theme->SetContent($content);
$theme->AppendContent($script);

$MIOLO->SetTheme($theme);

$MIOLO->GenerateTheme();

?>
