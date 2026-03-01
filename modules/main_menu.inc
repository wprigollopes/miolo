<?
/**
 *  Put in this file your menus/links for the Main Menu
 */

$session = $MIOLO->session;
$session->setValue("num_mainmenu",0);

$menu = $theme->getMainMenu();
$menu->setTitle( _M('Main Menu') );

$login = $MIOLO->getLogin();
$adminModule   = $MIOLO->mad;
$loginModule   = $MIOLO->getConf('login.module');
$startUpModule = $MIOLO->getConf('options.startup');
$commonModule  = $MIOLO->getConf('options.common');

//   $menu->addUserOption('MIOLOWIDE',A_ACCESS,'MIOLOWIDE','miolowide','main');
$menu->addOption( _M('Common'), $commonModule , 'main','','','common-16x16.png');
//$menu->addOption( _M('Hello World'), 'helloworld', 'main','','','helloworld-16x16.png');
//$menu->addOption( _M('Hangman'), 'hangman', 'main','','','hangman-16x16.png');

$tutMenu = $menu->getMenu('tutorial');
$tutMenu->setTitle( _M('Tutorial'), 'tutorial-16x16.png', null, 'tutorial', 'main' );
$tutMenu->addOption( _M('Validators'), $module, 'main:validators');
$tutMenu->addOption( _M('ezPDF'), $module, 'main:ezpdf');

$menuControls = $tutMenu->getMenu('controls');
$menuControls->setTitle('Controls','button_properties.png', 'button_edit.png');
$menuControls->addOption( _M('Block Controls'), $module, 'main:controls:blockcontrols', '','', 'button_properties.png');
$menuControls->addOption( _M('Menu Controls'), $module, 'main:controls:menu', '','', 'button_properties.png');

//gera o main_menu a partir do main_menu.xml
$dom = new DOMDocument();
$dom->load($MIOLO->getConf('home.modules') . '/modules_menu.xml');
$xpath = new DOMXPath($dom);
$link = $xpath->query('menu');

foreach ($link as $l)
{
    $fn = $xpath->query('caption',$l);
    $menuName = $fn->item(0)->firstChild->nodeValue;

    $fn = $xpath->query('module',$l);
    $menuOption = $fn->item(0)->firstChild->nodeValue;

    $fn = $xpath->query('action',$l);
    $menuAction = $fn->item(0)->firstChild->nodeValue;

    $fn = $xpath->query('icon',$l);
    $menuIcon = $fn->item(0)->firstChild->nodeValue;

    $menu->addOption( _M($menuName), $menuOption, $menuAction, '', '', $menuIcon );
}


// Create a new menu
$sysMenu = $theme->getMenu('system');
$sysMenu->setTitle( _M('System') );

if ( $perms->checkAccess($adminModule, A_ADMIN, false) )
{
    $sysMenu->addOption( _M('Administration'), $adminModule, 'main','','','system-16x16.png');
    $sysMenu->addSeparator('-');
}

if ($login)
{
   $sysMenu->addOption( _M('Logout'), $loginModule,'logout','','','logout-16x16.png');
}
else
{
   $sysMenu->AddOption( _M('Login'), $loginModule, 'login','','','login-16x16.png');
}

$develMenu = $theme->getMenu( 'devel' );
$develMenu->setTitle( _M('Development') );
$develMenu->addLink( _M('MIOLO Project Site'), 'http://www.miolo.org.br', '_new', 'miolo_icon.png' );
$develMenu->addSeparator();
//$develMenu->addLink( _M('Documentation'), 'http://www.miolo.org.br/handler.php?module=sites&action=view&article=12&sitesdbname=site_miolo', '_new', 'tutorial-16x16.png' );

$docMenu = $develMenu->getMenu('documentation');
$docMenu->setTitle( _M('Documentation') );
$docMenu->addLink( _M('Classes Diagram') , 'http://www.miolo.org.br/doc_MIOLO2/html/'                                        , '_new', 'tutorial-16x16.png' );
$docMenu->addLink( _M('MIOLO 2 Overview'), 'http://www.miolo.org.br/modules/sites/files/files/site_miolo/miolo2_overview.pdf', '_new', 'tutorial-16x16.png' );
$docMenu->addLink( _M('Reference Guide') , 'http://www.miolo.org.br/modules/sites/files/files/site_miolo/RefGuide.pdf'       , '_new', 'tutorial-16x16.png' );
$docMenu->addLink( _M('Database - DAO')  , 'http://www.miolo.org.br/modules/sites/files/files/site_miolo/miolo20_dao.pdf'    , '_new', 'tutorial-16x16.png' );

?>
