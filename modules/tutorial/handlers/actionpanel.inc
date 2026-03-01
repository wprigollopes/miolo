<?
$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('ActionPanel', $module, $self);
$action = $MIOLO->GetActionURL($module,'main');
$panel = new ActionPanel('pnlExample','Exemplo de ActionPanel');
$panel->AddAction('Action1 Globais',$ui->GetImage('','button_edit.png'),$module,'main:varglobais');
$panel->AddAction('Action1 Prompt',$ui->GetImage('','button_edit.png'),$module,'main:prompt');
$panel->AddAction('Action1 Panel',$ui->GetImage('','button_edit.png'),$module,'main:panel');
$theme->InsertContent($panel);
?>