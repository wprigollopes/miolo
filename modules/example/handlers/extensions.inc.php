<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Extensions', $module, 'main:extensions');
    $formSave = $ui->getForm($module,'frmExtensions');
	if ($theme->getContent()  == '')
	{
		$theme->insertContent($formSave);
	}
