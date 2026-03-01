<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Date & Time', $module, 'main:datetime');
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
		new MLabel('['.$k->KDate('%d de %F de %Y', $k->dateToTimestamp('15/03/2006')).']'),
		new MLabel('['.$k->KDate('%d %M %Y', $k->dateToTimestamp('15/03/2006')).']'),
		new MSeparator(),
		new MText('','Obtendo o nome do mês 5:'),
		new MLabel('['.$k->getMonthByNumber('5').']'),
		new MLabel('['.$k->getMonthByNumber('05').']'),
		new MSeparator(),
		new MText('','Obtendo o último dia do mês:'),
		new MLabel('['.$k->getLastDayOfMonth('2','2000').']'),
		new MLabel('['.$k->getLastDayOfMonth('2','2003').']'),
		new MLabel('['.$k->getLastDayOfMonth('2').']'),
		new MLabel('['.$k->getLastDayOfMonth('10').']'),
		new MSeparator(),
		new MText('','Invertendo a data 24/01/2006:'),
		new MLabel($k->invertDate('24/01/2006')),
		new MSeparator(),
		new MText('','Invertendo a data 2006/01/24:'),
		new MLabel($k->invertDate('2006/01/24')),
		new MSeparator(),
		new MText('','Unix timestamp 24/01/2006:'),
		new MLabel($k->dateToTimestamp('2006/01/24')),
		new MSeparator(),
		new MText('','Data/hora atuais:'),
		new MLabel($k->KDate('%d/%m/%Y %H:%i:00')),
		new MSeparator(),
		new MText('','Quantos dias existem entre 20/01/2006 e 06/02/2006?'),
		new MLabel($k->daysDiff('20/01/2006','06/02/2006')),
		new MSeparator(),
		new MText('','Adicionando 2 horas a 16:20: '),
		new MLabel($k->timesAdd('16:20','2:00','h')),
		new MSeparator(),
		new MText('','Adicionando 20 minutos a 16:20: '),
		new MLabel($k->timesAdd('16.20','20','m')),
		new MSeparator(),
		new MText('','Adicionando 1 hora e 15 minutos a 16:20:'),
		new MLabel($k->timesAdd('16:20','1:15','t')),
		new MSeparator(),
		new MText('','Quantos dias faltam para o natal?'),
		new MLabel($k->howTo('25/12/' . $k->KDate('%Y') )),
		new MSeparator(),
		new MText('','Adicionando 1 mês a 25/01/2006: '),
		new MLabel($k->operation('+','25/01/2006','1%M')),
		new MSeparator(),
		new MText('','Adicionando 45 dias a 25/01/2006: '),
		new MLabel($k->operation('+','25/01/2006','45%D')),
		new MSeparator(),
		new MText('','Adicionando 5 anos a 25/01/2006: '),
		new MLabel($k->operation('+','25/01/2006','5%Y')),
		new MSeparator(),
		new MText('','Subtraindo 1 mês a 25/01/2006: '),
		new MLabel($k->operation('-','25/01/2006','1%M')),
		new MSeparator(),
		new MText('','Subtraindo 45 dias a 25/01/2006: '),
		new MLabel($k->operation('-','25/01/2006','45%D')),
		new MSeparator(),
		new MText('','Subtraindo 5 anos a 25/01/2006: '),
		new MLabel($k->operation('-','25/01/2006','5%Y')),
		new MSeparator(),
	
	);
	$panel->addControl($controls,'100%','left');
	
	$theme->appendContent($panel);
