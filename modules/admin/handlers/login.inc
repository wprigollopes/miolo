<?
$MIOLO->LogMessage('[LOGIN] file:'.$_SERVER['SCRIPT_NAME']);
$MIOLO->LogMessage('[LOGIN] Using login prompt');
$module = 'admin';
$ui = $MIOLO->GetUI();
$theme->ClearContent();
$navbar->AddOption('Login', $module, 'login');
$login = $auth->GetLogin();

// If the user is logged in, send to the start up module
if ($login)
{
   //$form = $ui->GetForm($module,'frmAccess',$login);
   $MIOLO->invokeHandler($MIOLO->getConf('options.common'), 'main');
}
elseif ($MIOLO->GetConf('options.authmd5'))
{
   $form = $ui->GetForm($module,'frmLoginMD5');
}
else
{
   $form = $ui->GetForm($module,'frmLogin');
}
if ($theme->GetContent()  == '')
{
   $theme->InsertContent($form);
}
?>
