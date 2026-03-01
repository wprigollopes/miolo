<?php

$MIOLO->Trace('file:'.$_SERVER['SCRIPT_NAME']);

if ( ! $module )
{
    $module = 'admin';
}

if (($sa = $context->ShiftAction()) != NULL )
{
    $a = $sa;
}
elseif ($module != 'admin')
{
    $a = 'main';
}

$MIOLO->checkAccess('admin',A_ACCESS,true);

$navbar->AddOption( _M('Administration', $module) ,$module, 'main');


$ui = $MIOLO->GetUI();

$theme->ClearContent();

$close = $MIOLO->GetActionURL($module,'main');
$cmPanel = new ActionPanel('pnlAdmin', _M('Administration Module',$module),'', $close, $ui->GetImage($module,'system-16x16.png'));
$cmPanel->AddAction( _M('Modules', $module), $ui->getImage($module, 'modules-32x32.png'), $module, 'main:modules');
$cmPanel->AddAction( _M('Users', $module)  , $ui->getImage($module, 'user-32x32.png')  , $module, 'main:users');
$cmPanel->AddAction( _M('Groups', $module) , $ui->getImage($module, 'groups-32x32.png'), $module, 'main:groups');
$cmPanel->AddAction( _M('Custom Fields', $module) , $ui->getImage($module, 'custom-field-32x32.png'), $module, 'main:customfield');
$cmPanel->AddAction( _M('Database Dump', $module) , $ui->getImage($module, 'dbdump-32x32.png'), $module, 'main:dbdump');
$cmPanel->AddAction( _M('Configuration (miolo.conf)', $module) , $ui->getImage($module, 'conf-32x32.png'), $module, 'main:conf');
$cmPanel->AddAction( _M('Logs', $module) , $ui->getImage($module, 'log-32x32.png'), $module, 'main:log');
$cmPanel->addAction( _M('Logout', $module) , $ui->getImage($module, 'logout-32x32.png'), $odule, 'logout');
$handled = $MIOLO->InvokeHandler($module,$a);
if (! $handled)
{
    $theme->InsertContent($cmPanel);
}
include_once($MIOLO->GetConf('home.modules') .'/main_menu.inc');
