<?php

/**
*  Put in this file your menus/links for the Main Menu
*/
$menu->SetTitle('Main Menu');
$menu->Addoption('Tutorial','tutorial','main');
//$menu->Addoption('Wiki','wiki','main');
$menu->AddOption('Examples','examples','main');

$sysMenu =& $theme->GetMenu('login');
$sysMenu->SetTitle('Sistema');

//if ( $MIOLO->CheckAccess('common', A_ADMIN, false) )
{
    $sysMenu->AddOption(_M('Administration'),'common','main:admin');
    $sysMenu->AddSeparator();
}

//if ( $MIOLO->CheckAccess('common', A_DEVELOP, false) )
{
    $devMenu =& $theme->GetMenu('develop');
    $devMenu->SetTitle('Development');
    $devMenu->AddOption('Documentation', 'common','main:doc');
    $devMenu->AddSeparator();
    $devMenu->AddLink('Homepage', 'http://www.miolo.org.br/','home');
    $devMenu->AddLink('CVS', 'http://cvs.miolo.codigolivre.org.br/cgi-bin/cvsweb.cgi/?cvsroot=miolo','cvs');
}

?>
