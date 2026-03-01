<?
$MIOLO->LogMessage('[LOGIN] file:'.$_SERVER['SCRIPT_NAME']);
$MIOLO->LogMessage('[LOGIN] Using login prompt');
$ui = $MIOLO->GetUI();
$theme->ClearContent();
$navbar->AddOption('Login', 'admin', 'login');
$login = $auth->GetLogin();


if ($login)
{
   $form = $ui->GetForm('admin','frmAccess',$login);
}
elseif ($MIOLO->GetConf('options.authmd5'))
{
   $form = $ui->GetForm('admin','frmLoginMD5');
}
else
{
   $form = $ui->GetForm('admin','frmLogin');
}
if ($theme->GetContent()  == '')
{
   $theme->InsertContent($form);
}

//$MIOLO->page->generate();
//exit;
?>
