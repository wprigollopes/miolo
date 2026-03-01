<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Date & Time', $module, 'main:datetime');
	$k = new MKrono();
	
	$panel = new MPanel('pnlDummyDate','MKrono Features');
	$debug = array(
		new MTextLabel('debug',$k->_debug(),'Debug'),
	);
	
	$controls = array(
		new MBaseGroup('debugGrp','Information',$debug,'horizontal','css'),
		new MText('','Hoje é:'),
		new MLabel('['.$k->KDate('%d de %F de %Y').']'),
		new MLabel('['.$k->KDate('%d/%m/%Y').']'),
		new MLabel('['.$k->KDate('%l, %d %M %Y').']'),
		new MSeparator(),
		new MText('','Por extenso (15/03/2005):'),
		new MLabel('['.$k->KDate('%d de %F de %Y', $k->DateToTimestamp('15/03/2005')).']'),
		new MLabel('['.$k->KDate('%d %M %Y', $k->DateToTimestamp('15/03/2005')).']'),
		new MSeparator(),
		new MText('','Invertendo a data 24/01/2005:'),
		new MLabel($k->InvertDate('24/01/2005')),
		new MSeparator(),
		new MText('','Invertendo a data 2005/01/24:'),
		new MLabel($k->InvertDate('2005/01/24')),
		new MSeparator(),
		new MText('','Unix timestamp 24/01/2005:'),
		new MLabel($k->DateToTimestamp('2005/01/24')),
		new MSeparator(),
		new MText('','Quantos dias existem entre 20/01/2005 e 06/02/2005?'),
		new MLabel($k->DaysDiff('20/01/2005','06/02/2005')),
		new MSeparator(),
		new MText('','Adicionando 2 horas a 16:20: '),
		new MLabel($k->TimesAdd('16.20','2','h')),
		new MSeparator(),
		new MText('','Adicionando 20 minutos a 16:20: '),
		new MLabel($k->TimesAdd('16.20','20','m')),
		new MSeparator(),
		new MText('','Adicionando 1 hora e 15 minutos a 16:20:'),
		new MLabel($k->TimesAdd('16.20','1.15','t')),
		new MSeparator(),
		new MText('','Quantos dias faltam para o natal?'),
		new MLabel($k->HowTo('25/12/2005')),
		new MSeparator(),
		new MText('','Adicionando 1 mês a 25/01/2005: '),
		new MLabel($k->Operation('+','25/01/2005','1%M')),
		new MSeparator(),
		new MText('','Adicionando 45 dias a 25/01/2005: '),
		new MLabel($k->Operation('+','25/01/2005','45%D')),
		new MSeparator(),
		new MText('','Adicionando 5 anos a 25/01/2005: '),
		new MLabel($k->Operation('+','25/01/2005','5%Y')),
		new MSeparator(),
		new MText('','Subtraindo 1 mês a 25/01/2005: '),
		new MLabel($k->Operation('-','25/01/2005','1%M')),
		new MSeparator(),
		new MText('','Subtraindo 45 dias a 25/01/2005: '),
		new MLabel($k->Operation('-','25/01/2005','45%D')),
		new MSeparator(),
		new MText('','Subtraindo 5 anos a 25/01/2005: '),
		new MLabel($k->Operation('-','25/01/2005','5%Y')),
		new MSeparator(),
	
	);
	$panel->AddControl($controls,'100%','left');
	
	$theme->AppendContent($panel);

?>
