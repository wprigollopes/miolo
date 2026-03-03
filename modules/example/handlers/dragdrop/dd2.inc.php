<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Sample 2 - with Ajax', $module, $self);
    $form = $ui->getForm($module,'frmDragDrop2');
	$theme->insertContent($form);
?>