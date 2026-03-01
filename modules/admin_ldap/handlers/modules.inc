<?

$MIOLO->CheckAccess('module',A_ACCESS,true);
$navbar->AddOption('Modules',$module,$self);
$home = 'main:admin';
$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmModule');
$theme->AppendContent($form);
?>
