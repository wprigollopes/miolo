<?
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Captcha', $module, $self);
$form = $ui->getForm($module, 'frmCaptcha');
$theme->insertContent($form);
