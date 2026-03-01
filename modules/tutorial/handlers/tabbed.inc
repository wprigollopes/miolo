<?
// +-----------------------------------------------------------------+
// | MIOLO - Miolo Development Team - UNIVATES Centro Universitario  |
// +-----------------------------------------------------------------+
// | CopyLeft (L) 2001,2002 UNIVATES, Lajeado/RS - Brasil            |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigolivre.org.br                           |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// |                                                                 |
// | Created: Vilson Cristiano Gartner, Thomas Spriestersbach        |
// |                                                                 |
// +-----------------------------------------------------------------+
// $Id: tabbed.inc,v 1.1 2002/09/16 18:06:05 vgartner Exp $

    // adicionando link na barra de navegação, que aponte para o arquivo atual
    $navbar->AddOption('Exemplo TabbedForm', 'tutorial', 'main:tabbed');
    
    $MIOLO->GetUI();
  
    $form = new TabbedForm();
 
    $form->SetTitle('Exemplo de TabbedForm');
  
    $form->AddPage('Dados Pessoais',
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
                        'SP' => 'São Paulo');

    $form->AddPage('Dados Residenciais',
                   array(new TextField('Endereço','address', null, 40),
                         new TextField('Complemento','compl'),
                         new TextField('Cidade','city', null, 25),
                         new Selection('UF','uf',Form::GetFormValue('uf'),$options_uf)
                         )
                  );

    $theme->SetContent($form);

    if ( $form->IsSubmitted() )
    {
        $content = Prompt::Information('O formulário foi enviado, mas não existe nenhuma rotina implementada.... ');
        $theme->InsertContent( $content );
    }
  
?>
