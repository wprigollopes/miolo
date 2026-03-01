<?
$ui = $MIOLO->getUI();
$navbar->addOption('File Source', $module, $self);
$theme->clearContent();
$src = new FileContent($item, true);
$div = new Div('',$src);
$div->addStyle('margin-left','20px');
$div->addStyle('margin-right','20px');
$div->addStyle('border','1px solid black');
$div->addStyle('padding','10px');
$div->addStyle('font-size','10pt');
$theme->setContent($div);
$theme->appendContent(new MButton('btnClose','Fechar','javascript:miolo.getWindow(\'\').close();'));
$theme->setLayout('Popup');
