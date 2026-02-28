<?
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Panel', $module, $self);
$action = $MIOLO->getActionURL($module,'main');
$panel = new Panel('pnlExample','Exemplo de Panel');
$panel->addControl(new ImageLinkLabel('imgExample1','Image<br>Example', $action,$ui->getImage('','button_edit.png')));
$panel->addControl(new ImageLinkLabel('imgExample2','Ex',$action,$ui->getImage('','button_drop.png')));
$panel->addControl(new ImageLinkLabel('imgExample3','Image Example',$action,$ui->getImage('','button_browse.png')));
$panel->addControl(new ImageLinkLabel('imgExample4','Image Example',$action,$ui->getImage('','button_empty.png')),'70px',false);
$panel->addControl(new ImageLinkLabel('imgExample5','Image Examp',$action,$ui->getImage('','button_fulltext.png')));
$panel->addControl(new ImageLinkLabel('imgExample6','Example',$action,$ui->getImage('','button_index.png')));
$panel->addControl(new ImageLinkLabel('imgExample7','Image Example',$action,$ui->getImage('','button_insert.png')),'70px',false);
$panel->addControl(new ImageLinkLabel('imgExample8','Example',$action,$ui->getImage('','button_primary.png')));
$panel->addControl(new ImageLinkLabel('imgExample9','Image Example',$action,$ui->getImage('','button_select.png')));
$panel->addControl(new ImageLinkLabel('imgExample10','Image Example',$action,$ui->getImage('','button_properties.png')),'70px',false);
$panel->addControl(new ImageLinkLabel('imgExample11','Image Example',$action,$ui->getImage('','button_unique.png')));
$panel->addControl(new ImageLinkLabel('imgExample12','E',$action,$ui->getImage('','button_edit.png')));
$panel->addControl(new ImageLinkLabel('imgExample13','Image Example',$action,$ui->getImage('','button_drop.png')));
$panel->addControl(new ImageLinkLabel('imgExample14','Image Example',$action,$ui->getImage('','button_browse.png')));
$theme->insertContent($panel);
?>