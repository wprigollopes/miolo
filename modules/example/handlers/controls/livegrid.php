<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('LiveGrid', $module, $self);
    $form = $ui->getForm($module,'frmLiveGrid');
	$theme->setContent($form);
