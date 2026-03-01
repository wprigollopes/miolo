<?
$perms->CheckAccess('groups',A_ACCESS,true);
$home = 'main:admin';
$navbar->AddOption('Log',$module,$self);
$ui = $MIOLO->GetUI();
//if( MIOLO::_REQUEST('pointer') )
//{
//    $form = $ui->GetForm($module,'frmLogInfo');
//}
//else
//{
    $form = $ui->GetForm($module,'frmLog');
//}
if( ! MIOLO::_REQUEST('pointer') )
{
    $theme->AppendContent($form);
}
?>
