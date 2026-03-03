<?
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Aluno', $module, $self);
$content[] = new MLabel('Handle Aluno','red',true);
$content[] = new MSpacer('10px');
$aluno = $MIOLO->getBusiness('example','aluno',$item);
$content[] = new MLabel('idAAluno: ' . $aluno->idAluno,'',true);
$content[] = new MSpacer('10px');
$content[] = new MLabel('Nome: ' . $aluno->nome,'green',true);
$content[] = new MSpacer('10px');
$event = MIOLO::_request('event');
$content[] = new MLabel('event: ' . $event ,'blue',true);
$content[] = new MSpacer('10px');
$url1 = $MIOLO->getActionURL('example','main:controls:grid2');
$url2 = $MIOLO->getActionURL('example','main:controls:grid3');
$content[] = new MButton('btnBack1','Voltar para grid2', $url1);
$content[] = new MButton('btnBack2','Voltar para grid3', $url2);
$content[] = new MButton('btnClose','Fechar esta janela', 'window.close();');
$theme->insertContent($content);
?>