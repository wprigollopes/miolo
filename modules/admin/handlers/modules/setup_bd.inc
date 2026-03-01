<?

$theme->clearContent();



$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('Module Setup Data Base'), $module, 'main:modules:setup_bd');

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmSetupModuleBD');
$theme->AppendContent($form);

?>