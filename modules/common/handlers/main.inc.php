<?php
$MIOLO->Trace('file:' . $_SERVER['SCRIPT_NAME']);

if ( !$module )
{
    $module = 'common';
}

if ( ($sa = $context->shiftAction() ) != NULL )
{
    $a = $sa;
}
elseif ( $module != 'common' )
{
    $a = 'main';
}

$ui = $MIOLO->getUI();
$login = $MIOLO->getLogin();
$adminModule = $MIOLO->mad;
$loginModule = $MIOLO->getConf('login.module');

$theme->clearContent();

$handled = $MIOLO->invokeHandler($module, $a);

if ( !$handled )
{
    $cmPanel = new MActionPanel('pnlCommon', _M('Common Environment', $module), '', $close, $ui->getImage($module, 'common-16x16.png'));

    $cmPanel->addAction(_M('Hello World', $module), $ui->getImage('helloworld', 'helloworld-32x32.png'), 'helloworld', 'main');
    $cmPanel->addAction(_M('Hangman', $module), $ui->getImage('hangman', 'hangman-32x32.png'), 'hangman', 'main');
    $cmPanel->addAction(_M('Example', $module), $ui->getImage('example', 'tutorial-32x32.png'), 'example', 'main');
    $cmPanel->addAction(_M('Generator', $module), $ui->getImage('generator', 'generator-32x32.png'), 'generator', 'main');
    $cmPanel->addAction(_M('Mobile', $module), $ui->getImage('mobileexample', 'mobile-32x32.png'), 'mobileexample', 'main');

    if ( $login )
    {
        $cmPanel->addAction(_M('Administration', $module), $ui->getImage($adminModule, 'system-32x32.png'), $adminModule, 'main');
        $cmPanel->addAction(_M('Logout', $module), $ui->getImage($loginModule, 'logout-32x32.png'), $loginModule, 'logout');
    }
    else
    {
        $cmPanel->addAction(_M('Login', $module), $ui->getImage($loginModule, 'login-32x32.png'), $loginModule, 'login');
    }

    $theme->insertContent($cmPanel);
}

?>