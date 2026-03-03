<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Theme Skin', $module, 'main:themeskin');
    $formSave = $ui->getForm($module,'frmThemeSkin');
	if ($theme->getContent()  == '')
	{
		$theme->insertContent($formSave);
	}
?>
