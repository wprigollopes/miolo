<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('Menus', $module, $self);
$theme->ClearContent();
$theme->InsertContent(new MContentHeader('Tutorial','Menus'));
$close = $MIOLO->GetActionURL($module,'main');
$icon = $ui->GetImage('','button_edit.png');
$frmPanel = new MActionPanel('pnlMenus','Menus','',$close, $icon);
$frmPanel->AddAction('Menu',$ui->GetImage('','button_edit.png'),$module,'menus:menu');
$theme->AppendContent($frmPanel);
$handled = $MIOLO->InvokeHandler($module, 'menus/' . $context->ShiftAction());
?>
