<?

if ($MIOLO->getAuth()->isLogged())
{
    $MIOLO->getPerms()->checkAccess('LoginNeeded',A_ACCESS,true);
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Login Needed', $module, $self);
    $text = new MLabel('A simple example of a handler that needs a logged/authenticated user');
    $theme->appendContent($text);
}
else
{
	$url = $MIOLO->getCurrentURL(); 
    $ui = $MIOLO->getUI();
    $form = $ui->getForm($MIOLO->getConf('login.module'),'frmLogin');
    $form->setFieldValue('return_to', $url);
    $theme->setContent($form);
}
