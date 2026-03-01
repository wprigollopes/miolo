<?

$theme->clearContent();

$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('Remove Module Options'), $module, 'main:modules:rem_modules:rem_modules_options');

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmRemModuleOptions');
$theme->AppendContent($form);

?>
