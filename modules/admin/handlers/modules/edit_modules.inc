<?

$MIOLO->CheckAccess('module',A_ACCESS,true);

$home = 'main:admin';
$navbar->AddOption( _M('Modules'), $module, $home);

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmModule');
$theme->AppendContent($form);
?>
