<?
$ui = $MIOLO->getUI();
$navbar->addOption('Structure Controls', $module, $self);
$theme->breakContent();
$panel = new Panel('pnlDummy','Web Controls');
$span = new Span('','Span Content');
$span->addStyle('color','blue');
$div = new Div('','A DIV with a few text');
$div->addStyle('float','right');
$div->addStyle('background-color','#CCC');
$div2 = new Div('','Internal');
$div2->addStyle('background-color','#999900');
$div2->addStyle('width','100px');
$div2->addStyle('color','#FFF');
$div3 = new Div('',$div2);
$div3->addStyle('background-color','#DDD');
$div3->addStyle('width','300px');
$div3->addStyle('padding','5px');
$div3->addStyle('float','left');
$controls = array(
    new Text('','A Span with style: '),
    $span,
    new Separator(),
    new Text('','A Div with float:right and bgcolor: '),
    $div,
    new Separator(),
    new Text('','A nested Div: '),
    $div3,
);
$panel->addControl($controls,'100%','clear');
$theme->appendContent($panel);
$src = new ViewSource(__FILE__);
$theme->appendContent($src);
