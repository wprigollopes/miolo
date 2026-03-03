<?
$ui = $MIOLO->getUI();
$navbar->addOption('Theme Elements', $module, $self);
$theme->clearContent();
$theme->insertContent(new ContentHeader('example','Theme Elements'));
$close = $MIOLO->getActionURL($module,'main');
$icon = $ui->getImage('','button_edit.png');
$ctlPanel = new ActionPanel('pnlTheme','Theme Elements','',$close, $icon);
$ctlPanel->addAction('ActionPanel',$ui->getImage('','button_edit.png'),$module,'themeelement:actionpanel');
$ctlPanel->addAction('Container',$ui->getImage('','button_edit.png'),$module,'themeelement:container');
$ctlPanel->addAction('Content',$ui->getImage('','button_edit.png'),$module,'themeelement:content');
$ctlPanel->addAction('FileContent',$ui->getImage('','button_edit.png'),$module,'themeelement:filecontent');
$ctlPanel->addAction('Prompt',$ui->getImage('','button_edit.png'),$module,'themeelement:prompt');
$ctlPanel->addAction('Status',$ui->getImage('','button_edit.png'),$module,'themeelement:status');
$ctlPanel->addAction('ThemeBox',$ui->getImage('','button_edit.png'),$module,'themeelement:themebox');
$ctlPanel->addAction('SimpleTable',$ui->getImage('','button_edit.png'),$module,'themeelement:simpletable');
$theme->appendContent($ctlPanel);
$handled = $MIOLO->invokeHandler($module, 'controls/' . $context->shiftAction());
?>