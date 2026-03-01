<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('Theme Elements', $module, $self);
$theme->ClearContent();
$theme->InsertContent(new ContentHeader('Tutorial','Theme Elements'));
$close = $MIOLO->GetActionURL($module,'main');
$icon = $ui->GetImage('','button_edit.png');
$ctlPanel = new ActionPanel('pnlTheme','Theme Elements','',$close, $icon);
$ctlPanel->AddAction('ActionPanel',$ui->GetImage('','button_edit.png'),$module,'themeelement:actionpanel');
$ctlPanel->AddAction('Container',$ui->GetImage('','button_edit.png'),$module,'themeelement:container');
$ctlPanel->AddAction('Content',$ui->GetImage('','button_edit.png'),$module,'themeelement:content');
$ctlPanel->AddAction('FileContent',$ui->GetImage('','button_edit.png'),$module,'themeelement:filecontent');
$ctlPanel->AddAction('Prompt',$ui->GetImage('','button_edit.png'),$module,'themeelement:prompt');
$ctlPanel->AddAction('Status',$ui->GetImage('','button_edit.png'),$module,'themeelement:status');
$ctlPanel->AddAction('ThemeBox',$ui->GetImage('','button_edit.png'),$module,'themeelement:themebox');
$ctlPanel->AddAction('SimpleTable',$ui->GetImage('','button_edit.png'),$module,'themeelement:simpletable');
$theme->AppendContent($ctlPanel);
$handled = $MIOLO->InvokeHandler($module, 'controls/' . $context->ShiftAction());
?>