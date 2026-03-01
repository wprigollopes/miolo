<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Exceptions', $module, 'main:exceptions');
    $formSave = $ui->getForm($module,'frmExceptions');
	if ($theme->getContent()  == '')
	{
		$theme->insertContent($formSave);
	}
