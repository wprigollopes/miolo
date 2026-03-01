<?
// Se handled, pode retornar

$handled = $MIOLO->invokeHandler($module, 'windows/' . $context->shiftAction());
if ($handled) return true;

// Se not handled, construir o panel

$ui = $MIOLO->getUI();
$navbar->addOption('Windows', $module, 'main:windows');
$theme->clearContent();
$close = $MIOLO->getActionURL($module,'main');
$icon = $ui->getImage('','button_edit.png');
$frmPanel = new MActionPanel('pnlWindows','Windows','',$close, $icon);

//
// Accessing a form in a modal window
//
$urlWindow = $MIOLO->getActionURL('example','main:windows:window'); 
$win = new MWindow('winWindow',array('url'=>$urlWindow));
$win->setStatusBar(new MLabel('Status Bar'));
$frmPanel->addLink('Window',$ui->getImage($module,'forms.png'),$win->getLink(true, false));

$frmPanel->addAction('Modal_Ajax',$ui->getImage($module,'forms.png'),$module,'main:windows:modal_ajax');


$theme->appendContent($frmPanel);

?>