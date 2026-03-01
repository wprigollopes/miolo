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

    // adicionando link na barra de navegacao, que aponte para o arquivo atual
    $navbar->AddOption('Exemplo TabbedForm', 'examples', 'main:tabbed');
    
    $MIOLO->GetUI();
  
    $form = new TabbedForm();
  
    $form->SetTitle('Exemplo de TabbedForm');
    
    $names[0][] = new Option('Vilson', 'name', 1);
    $names[0][] = new Option('Nasair', 'name', 2);
    $names[0][] = new Option('Jamiel', 'name', 3);
    $names[0][] = new Option('Xande', 'name', 4);
    
  
    $form->AddPage('Dados Pessoais',
                   array(new TextField('Nome','name',null, 40),
                         new TextField('Nome Pai','father',null, 40),
                         new TextField('Nome Mae','mother',null, 40),
                         new RadioButtonGroup('Estado Civil', 'married', array('Solteiro', 'Casado', 'Viuvo', 'Separado', 'Enrolado ;-)')),
                         new RadioButtonGroup('Nome', 'nome', $names[0], 1)
                        )
                  );

    // definindo lista de UFs
    $options_uf = array(''   => '--- Selecione ---',
                        'AC' => 'Acre',
                        'AM' => 'Amazonas',
                        'DF' => 'Distrito Federal',
                        'MG' => 'Minas Gerais',
                        'PB' => 'Pernambuco',
                        'PR' => 'Parana',
                        'RJ' => 'Rio de Janeiro',
                        'RS' => 'Rio Grande do Sul',
                        'SC' => 'Santa Catarina',
                        'SP' => 'Sao Paulo');

    $btn_labels  = array ("Add","Remove","Enviar");
    $btn_names   = array ("add_","remove_","send_");
    $btn_actions = array ("add_btn()","RESET","SUBMIT");
    

    $form->AddPage('Dados Residenciais',
                   array(new TextField('Endereco','address', null, 40),
                         new TextField('Complemento','compl'),
                         new TextField('Cidade','city', null, 25),
                         new Selection('UF','uf',Form::GetFormValue('uf'),$options_uf),
                         new PageButton('Action','Add','add_btn','add_btn()'),
                         new PageButton('&nbsp;','Limpar','cls_btn','RESET'),
                         new Separator('<hr>'),
                         new PageButton('Actions', $btn_labels, $btn_names, $btn_actions)
                         )
                  );
                  
    //Javascript
    echo "<script language=\"Javascript\">\n";
    echo "    function add_requeriment_id_" . $data2->requeriment_id . "() {\n";
    echo "        alert('add');\n";
    echo "    }\n";
    echo "</script>\n";                        
                  
    $action = MIOLO::GetCurrentURL();
    
    $form->SetAction($action);
    
    $theme->SetContent($form);
  
?>
