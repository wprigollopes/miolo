<?php
$perms->CheckAccess('admin_ldap',A_ACCESS,true);

$navbar->AddOption('Administração',$module,'main:admin');
$ui = $MIOLO->getUI();


$close = $MIOLO->GetActionURL('admin_ldap','main');
$cmPanel = new ActionPanel('pnlCommon','Administração de permissões LDAP','', $close, $ui->GetImage($module,'tools1.png'));
//$cmPanel->AddAction( 'Modules', $ui->GetImage($module,'system.png'), $module, 'main:modules');
$cmPanel->AddAction( 'Usuários', $ui->GetImage($module,'user.png'), $module, 'main:users');
$cmPanel->AddAction( 'Grupos', $ui->GetImage($module,'groups.png'), $module, 'main:groups');

$handled = $MIOLO->InvokeHandler($module, $context->ShiftAction());

if (! $handled)
{
    $theme->appendContent($cmPanel);
}


/*
$MIOLO->Trace('file:'.$_SERVER['SCRIPT_NAME']);

$ui = $MIOLO->GetUI();
   
$theme->ClearContent();
$a = $context->shiftAction();

if( !($handled = $MIOLO->InvokeHandler($module,$a)) )
{
    $image   = new MImage('logo_miolo',_M('MIOLO'),$ui->getImage('admin','logo_miolo_big.png'));
    $content = new MDiv('logo_miolo',$image,'m-prompt-box');
    $theme->insertContent($content);
}
*/
?>
