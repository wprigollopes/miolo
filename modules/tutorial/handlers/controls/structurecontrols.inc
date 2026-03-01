<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('Structure Controls', $module, $self);
$theme->BreakContent();
$panel = new Panel('pnlDummy','Web Controls');
$span = new Span('','Span Content');
$span->AddStyle('color','blue');
$div = new Div('','A DIV with a few text');
$div->AddStyle('float','right');
$div->AddStyle('background-color','#CCC');
$div2 = new Div('','Internal');
$div2->AddStyle('background-color','#999900');
$div2->AddStyle('width','100px');
$div2->AddStyle('color','#FFF');
$div3 = new Div('',$div2);
$div3->AddStyle('background-color','#DDD');
$div3->AddStyle('width','300px');
$div3->AddStyle('padding','5px');
$div3->AddStyle('float','left');
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
$panel->AddControl($controls,'100%','clear');
$theme->AppendContent($panel);
$src = new ViewSource(__FILE__);
$theme->AppendContent($src);

?>