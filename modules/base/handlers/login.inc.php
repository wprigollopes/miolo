<?php
$moduleRequisicao        = MIOLO::_REQUEST('module');

$encontrouModulo = false;
if( strlen($moduleRequisicao) > 0 )
{
    // Checks if there is a custom login screen for the given module (miolo20/html/login_modulo)
    if( is_dir($MIOLO->getAbsolutePath()."/html/login_".$module) )
    {
        $encontrouModulo = true;
        header("location:".$MIOLO->getConf('home.url')."/login_".$module);
    }
}
// Checks if there is a custom screen (miolo26/html/login_novo)
if( is_dir($MIOLO->getAbsolutePath()."/html/login_novo") && !$encontrouModulo )
{
    header("location:".$MIOLO->getConf('home.url')."/login_novo");
}

$classModule   = $moduleRequisicao . '.class.php';
$dirClass      = __DIR__ . '/../../' . $moduleRequisicao . '/classes/' . $classModule;
$accessChecked = false;

if ( file_exists($dirClass) )
{
    require_once $dirClass;
    $objModule = new $moduleRequisicao();
            
    if ( method_exists($objModule, 'isAllowedAction') )
    {
        $accessChecked = $objModule->isAllowedAction();
    }
}

$MIOLO->logMessage('[LOGIN] file:'.$_SERVER['SCRIPT_NAME']);
$MIOLO->logMessage('[LOGIN] Using login prompt');
$ui = $MIOLO->getUI();
$theme->clearContent();
$module = 'portal';
$navbar->addOption('Login', $module, 'login');
$login = $auth->getLogin();
$return_to = MIOLO::_REQUEST('return_to');


// If the user is logged in, send to the start up module
if ($login && ($return_to == '') || $accessChecked)
{
   //$form = $ui->getForm($module,'frmAccess',$login);
   $MIOLO->invokeHandler($MIOLO->getConf('options.common'), 'main');
}
else
{
    $formularioLogin = "frmLogin";
    
    // If the requested module is the evaluation module, considers the custom form
    if( $moduleRequisicao === "avinst" )
    {
        if( strlen(SAGU::getParameter("avinst", "FORMULARIO_DE_LOGIN_PERSONALIZADO")) > 0 )
        {
            $formularioLogin = SAGU::getParameter("avinst", "FORMULARIO_DE_LOGIN_PERSONALIZADO");
        }
        else
        {
            $url = $MIOLO->getCurrentURL();

            if ( (substr_count($url, 'module=avinst') > 0 || substr_count($url, 'avaliacao') > 0) )
            {
                // To not render the top bar of the evaluation theme
                $MIOLO->page->OnLoad(" document.getElementById('__mainForm_container_top').style.display = 'none'; ");

                $MIOLO->session->set('return_to', 'AVALIACAO');
                $url = $MIOLO->getActionURL('portal', 'main');
                $url = html_entity_decode($url);

                // Redirects to the portal so that the same theme is used for the evaluation and portal login
                $MIOLO->page->addJsCode(" window.location = '$url';");
                $formularioLogin = NULL;
            }
        }
    }

    if ( $formularioLogin )
    {
        $form = $ui->getForm($module, $formularioLogin);
    }
}
if ($theme->getContent()  == '')
{
   $theme->insertContent($form);
}
?>
