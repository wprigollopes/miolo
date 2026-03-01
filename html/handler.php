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

// When using scramble, we turn the variables
// into globals ( MIOLO::_REQUEST to get them)
if ( ( $MIOLOCONF['options']['scramble'] ) &&
     ( $_REQUEST['MIOLO_URI'] )
    )
{
    $url = MIOLO::UnScramble( $_REQUEST['MIOLO_URI'] );
    $url = explode('&', $url);
    
    foreach ( $url as $u )
    {
        $global = '$'.substr($u, 0, strpos($u, '=') );
        eval("global $global;");
        
        $u = urldecode($u);
        $u = '$'.$u."';";
        $u = ereg_replace('=',"='", $u);

        eval("$u");
    }
}
else
{
    $module = $_REQUEST['module'];
    $action = $_REQUEST['action'];
    $item   = $_REQUEST['item'];
    $pn     = $_REQUEST['pn'];
}

//$MIOLO->Dump($item,__FILE__,__LINE__);

// capture some statistics
$MIOLO->Trace("HTTP_REFERER='" . $_SERVER['HTTP_REFERER'] . "'");
$MIOLO->Trace("HTTP_USER_AGENT='".$_SERVER['HTTP_USER_AGENT']."'");

// Require do autoload do composer caso ele exista
$composerAutoload = $MIOLOCONF['home']['modules'].'/fermi/vendor/autoload.php';
if (file_exists($composerAutoload))
{
    require_once $composerAutoload;
}

// DotEnv load
$dotEnvFile = $MIOLOCONF['home']['modules'].'/fermi/.env';
if (file_exists($dotEnvFile))
{
    $dotEnv = new Dotenv\Dotenv(dirname($dotEnvFile));
    $dotEnv->load();
}

// customization of navigation bar's menu
$navbar->SetTitle( _M('Home') );

// invoke the main miolo file
$MIOLO->InvokeHandler($MIOLOCONF['options']['startup'],'main');
// generate the page
$MIOLO->GenerateTheme();

?>
