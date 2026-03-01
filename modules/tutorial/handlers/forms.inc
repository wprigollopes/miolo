<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('Forms', $module, 'main:forms');
$theme->ClearContent();
$theme->InsertContent(new MContentHeader('Tutorial','Forms'));
$close = $MIOLO->GetActionURL($module,'main');
$icon = $ui->GetImage('','button_edit.png');
$frmPanel = new ActionPanel('pnlForms','Forms','',$close, $icon);
$frmPanel->AddAction('StepByStep',$ui->GetImage('','button_edit.png'),$module,'main:forms:stepbystep');
$frmPanel->AddAction('Form',$ui->GetImage('','button_edit.png'),$module,'main:forms:form');
$frmPanel->AddAction('Form Zebra AutoPost',$ui->GetImage('','button_edit.png'),$module,'main:forms:formzebra');
$frmPanel->AddAction('Form HV Visibility',$ui->GetImage('','button_edit.png'),$module,'main:forms:formHV');
$frmPanel->AddAction('Form-HV Containers',$ui->GetImage('','button_edit.png'),$module,'main:forms:formHV2');
$frmPanel->AddAction('Complex Layout',$ui->GetImage('','button_edit.png'),$module,'main:forms:formComplex');
$frmPanel->AddAction('Tabbed Form',$ui->GetImage('','button_edit.png'),$module,'main:forms:tabbed');
$frmPanel->AddAction('Relative CSS Form',$ui->GetImage('','button_edit.png'),$module,'main:forms:css');
$frmPanel->AddAction('Complex Layout CSS',$ui->GetImage('','button_edit.png'),$module,'main:forms:formComplex2');
$frmPanel->AddAction('CSS Position Form - 0',$ui->GetImage('','button_edit.png'),$module,'main:forms:formPos0');
$frmPanel->AddAction('CSS Position Form - 1',$ui->GetImage('','button_edit.png'),$module,'main:forms:formPos1');
$frmPanel->AddAction('Lookup',$ui->GetImage('','button_edit.png'),$module,'main:forms:lookup');
$theme->AppendContent($frmPanel);
$handled = $MIOLO->InvokeHandler($module, 'forms/' . $context->ShiftAction());
?>
