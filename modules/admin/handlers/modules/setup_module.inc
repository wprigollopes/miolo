<?php
$MIOLO->Trace('file:'.$_SERVER['SCRIPT_NAME']);
 
$theme->clearContent();

$MIOLO->CheckAccess('module',A_ACCESS,true);

$navbar->AddOption( _M('Module Setup'), $module, 'main:modules:setup_module');

$ui   = $MIOLO->GetUI();

$form = $ui->GetForm($module,'frmSetupModule');
//$form->setAction();

$theme->AppendContent($form);

$a = $context->ShiftAction();

if ( ! $MIOLO->InvokeHandler($module,$a) )
{
    $theme->InsertContent($cmPanel);
}

?>
