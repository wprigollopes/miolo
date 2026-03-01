<?

$theme->clearContent();

$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('Module Information'), $module, 'main:modules:rem_modules:view_information_modules');

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmViewInformationModules');
$theme->AppendContent($form);


?>