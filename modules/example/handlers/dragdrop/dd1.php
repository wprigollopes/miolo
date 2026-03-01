<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Sample 1', $module, $self);
    $form = $ui->getForm($module,'frmDragDrop1');
	$theme->insertContent($form);
