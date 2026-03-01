<?
$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('ActionPanel', $module, $self);
$action = $MIOLO->GetActionURL($module,'main:themeelement');
$icon = $ui->GetImage('','button_edit.png');
$panel = new ActionPanel('pnlExample','Exemplo de ActionPanel', '', $action);
$panel->AddAction('Action Globais',$icon,$module,'main:varglobais');
$panel->AddAction('Action Prompt',$icon,$module,'themeelement:prompt');
$panel->AddAction('Action Buttons',$icon,$module,'controles:buttons');
$theme->InsertContent($panel);
?>