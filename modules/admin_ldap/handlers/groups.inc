<?
$perms->CheckAccess( $module,A_ACCESS,true);
$home = 'main:admin';
$navbar->AddOption('Groups',$module,$self);
$ui = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmGroup');
$theme->AppendContent($form);
?>
