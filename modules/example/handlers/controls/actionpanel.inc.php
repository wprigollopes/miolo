<?
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('ActionPanel', $module, $self);
$action = $MIOLO->getActionURL($module,'main:themeelement');
$icon = $ui->getImage('','button_edit.png');
$panel = new ActionPanel('pnlExample','Exemplo de ActionPanel', '', $action);
$panel->addAction('Action Globais',$icon,$module,'main:varglobais');
$panel->addAction('Action Prompt',$icon,$module,'themeelement:prompt');
$panel->addAction('Action Buttons',$icon,$module,'controles:buttons');
$theme->insertContent($panel);
