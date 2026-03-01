<?

$MIOLO->Trace('file:'.$_SERVER['SCRIPT_NAME']);

$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('View Available Modules'), $module, 'main:modules:add_online_modules');

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmAddOnlineModule');

if ( ! $MIOLO->InvokeHandler($module, $context->ShiftAction()) )
{
    $theme->InsertContent($form);
}

?>
