<?
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Tabbed Base Group', $module), $module, $self);
$form = $ui->getForm($module, 'frmTabbedBaseGroup');
$theme->insertContent($form);
?>
