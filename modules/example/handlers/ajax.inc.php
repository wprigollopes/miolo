<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('AJAX', $module, $self);
    $form = $ui->getForm($module,'frmAJAX');
	$theme->setContent($form);
?>
