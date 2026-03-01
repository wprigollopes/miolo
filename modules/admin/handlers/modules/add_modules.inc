<?

$MIOLO->Trace('file:'.$_SERVER['SCRIPT_NAME']);
 
$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('Add Modules'), $module, 'main:modules:add_modules');

$ui   = $MIOLO->GetUI();
$form = $ui->GetForm($module,'frmAddModule');
$theme->AppendContent($form);


$handled = $MIOLO->InvokeHandler($module, 'modules/'.$context->ShiftAction() );

if (! $handled)
{
    $theme->InsertContent($cmPanel);
}
include_once($MIOLO->GetConf('home.modules') .'/main_menu.inc');

?>
