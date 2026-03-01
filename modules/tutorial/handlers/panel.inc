<?
$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('Panel', $module, $self);
$action = $MIOLO->GetActionURL($module,'main');
$panel = new Panel('pnlExample','Exemplo de Panel');
$panel->AddControl(new ImageLinkLabel('imgExample1','Image<br>Example', $action,$ui->GetImage('','button_edit.png')));
$panel->AddControl(new ImageLinkLabel('imgExample2','Ex',$action,$ui->GetImage('','button_drop.png')));
$panel->AddControl(new ImageLinkLabel('imgExample3','Image Example',$action,$ui->GetImage('','button_browse.png')));
$panel->AddControl(new ImageLinkLabel('imgExample4','Image Example',$action,$ui->GetImage('','button_empty.png')),'70px',false);
$panel->AddControl(new ImageLinkLabel('imgExample5','Image Examp',$action,$ui->GetImage('','button_fulltext.png')));
$panel->AddControl(new ImageLinkLabel('imgExample6','Example',$action,$ui->GetImage('','button_index.png')));
$panel->AddControl(new ImageLinkLabel('imgExample7','Image Example',$action,$ui->GetImage('','button_insert.png')),'70px',false);
$panel->AddControl(new ImageLinkLabel('imgExample8','Example',$action,$ui->GetImage('','button_primary.png')));
$panel->AddControl(new ImageLinkLabel('imgExample9','Image Example',$action,$ui->GetImage('','button_select.png')));
$panel->AddControl(new ImageLinkLabel('imgExample10','Image Example',$action,$ui->GetImage('','button_properties.png')),'70px',false);
$panel->AddControl(new ImageLinkLabel('imgExample11','Image Example',$action,$ui->GetImage('','button_unique.png')));
$panel->AddControl(new ImageLinkLabel('imgExample12','E',$action,$ui->GetImage('','button_edit.png')));
$panel->AddControl(new ImageLinkLabel('imgExample13','Image Example',$action,$ui->GetImage('','button_drop.png')));
$panel->AddControl(new ImageLinkLabel('imgExample14','Image Example',$action,$ui->GetImage('','button_browse.png')));
$theme->InsertContent($panel);
?>