<?
// +-----------------------------------------------------------------+
// |         MIOLO - Miolo Development Team - SOLIS/UNIVATES         |
// +-----------------------------------------------------------------+
// | CopyLeft (L) 2003 SOLIS/UNIVATES, Lajeado/RS - Brasil           |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigolivre.org.br                           |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// |                                                                 |
// | Created: Vilson Cristiano Gartner - 12/06/2003                  |
// |                                                                 |
// +-----------------------------------------------------------------+
// $Id: tabbed2.inc,v 1.1 2005/05/17 03:22:41 vgartner Exp $

    // adicionando link na barra de navegação, que aponte para o arquivo atual
    $navbar->AddOption('TabbedForm 2 - DHTML', $module, $action);
    
    $MIOLO->GetUI();
  
    $form = new TabbedForm2();
 
    $form->SetTitle('Exemplo de DHTML TabbedForm');
    //$form->SetCSS('tab.webfx');
    //$form->SetCSS('tab');
    //$form->SetCSS('/luna/tab');
    //$form->SetCSS('tab.winclassic');
    $form->SetCSS($item);

  
    $form->AddPage('Personal Data',
                   array(new TextField('Nome','name',null, 40),
                         new TextField('Nome Pai','father',null, 40),
                         new TextField('Nome Mae','mother',null, 40),
                         new RadioButtonGroup('Estado Civil', 'married', array('Solteiro', 'Casado', 'Viúvo', 'Separado', 'Enrolado ;-)'))
                         )
                   );

    // definindo lista de UFs
    $options_uf = array(''   => '--- Selecione ---',
                        'AC' => 'Acre',
                        'AM' => 'Amazonas',
                        'DF' => 'Distrito Federal',
                        'MG' => 'Minas Gerais',
                        'PB' => 'Pernambuco',
                        'PR' => 'Paraná',
                        'RJ' => 'Rio de Janeiro',
                        'RS' => 'Rio Grande do Sul',
                        'SC' => 'Santa Catarina',
                        'SP' => 'São Paulo'
			);

    $form->AddPage('Dados Residenciais',
                   array(new TextField('Endereço','address', null, 40),
                         new TextField('Complemento','compl'),
                         new TextField('Cidade','city', null, 25),
                         new Selection('UF','uf',Form::GetFormValue('uf'),$options_uf)
                         )
                   );
    
    $act1 = $MIOLO->GetActionURL($module, $action, 'tab');
    $act2 = $MIOLO->GetActionURL($module, $action, '/luna/tab');
    
    $form->AddButton(new FormButton('Classic', 'btn1' , "GotoURL('$act1.winclassic')" ));
    $form->AddButton(new FormButton('WebFX'  , 'btn2' , "GotoURL('$act1.webfx')" ));
    $form->AddButton(new FormButton('Luna'   , 'btn3' , "GotoURL('$act2')" ));
    $form->AddButton(new FormButton('Simple' , 'btn4' , "GotoURL('$act1')" ));

    $theme->SetContent($form);

/*
    if ( Form::IsSubmitted() )
    {
        $content = Prompt::Information('O formulário foi enviado, mas não existe nenhuma rotina implementada.... ');
        $theme->InsertContent( $content );
    }
*/  
?>
