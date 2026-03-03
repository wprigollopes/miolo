<?
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Lightbox', $module, $self);
$form = $ui->getForm($module, 'frmLightbox');
$theme->insertContent($form);
?>
