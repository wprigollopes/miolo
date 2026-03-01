<?
$perms->CheckAccess('user',A_ACCESS,true);

$home = 'main:action';
$navbar->AddOption( _M('Users'), $module, $home);

$ui = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmUser');
$theme->AppendContent($form);
?>
