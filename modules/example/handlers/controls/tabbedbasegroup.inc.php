<?
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Tabbed BaseGroup', $module, $self);
$form = $ui->getForm($module, 'frmTabbedBaseGroup');
$theme->insertContent($form);
?>
