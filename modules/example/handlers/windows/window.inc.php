<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Window', $module, $self);
    $form1 = $ui->getForm($module,'frmWindow1');
	$theme->insertContent($form1);
?>