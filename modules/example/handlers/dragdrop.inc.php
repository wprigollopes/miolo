<?
// Se handled, pode retornar

$handled = $MIOLO->invokeHandler($module, 'dragdrop/' . $context->shiftAction());
if ($handled) return true;

// Se not handled, construir o panel

$ui = $MIOLO->getUI();
$navbar->addOption('Drag&Drop', $module, 'main:dragdrop');
$theme->clearContent();
$close = $MIOLO->getActionURL($module,'main');
$icon = $ui->getImage('','button_edit.png');
$frmPanel = new MActionPanel('pnlDragDrop','Drag&Drop','',$close, $icon);
$frmPanel->addAction('Sample 1',$ui->getImage($module,'forms.png'),$module,'main:dragdrop:dd1');
$frmPanel->addAction('Sample 2',$ui->getImage($module,'forms.png'),$module,'main:dragdrop:dd2');
$theme->appendContent($frmPanel);

?>