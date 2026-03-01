<?
    $array_combo = array( '1' => 'Viçosa', 
                          '2' => 'Ponte Nova',
                          '3' => 'Ouro Preto',
                          '4' => 'Belo Horizonte'
                         );
    
    $array_frutas = array( '0' => '--- Selecione ---',
                           '1' => 'Abacaxi', 
                           '2' => 'Mamão',
                           '3' => 'Pera',
                           '4' => 'Laranja'
                          );
    
                
    $MIOLO->GetUI();
    
    $form = new Form('Campos de Formulário');
    
    $form->AddField( new FileField('Arquivo','arquivo','',40) );
    $form->AddField( new ComboBox( 'Cidade', //rótulo
                                   'cidade', //name
                                   null,
                                   $array_combo ) );
    
    $form->AddField( new LookupField('Usuario',            // rotulo
                                     'usuario',            // nome campo
                                     'Nome',               // nome
                                     'examples',           // ID module
                                     'Login',              // ID item Autocompl
                                     null ) );             // valor
    
    $form->AddField( new TextField('Login','Nome',null,40,'Este campo será completado automaticamente quando o login for informado') );
    
    $form->AddField( new TextArea( 'Descrição', 'descricao', 'valor inicial',50,4) );
    $form->AddField( new PasswordField( 'Senha','senha',"" ,10) );
    
    $form->AddField( new Separator('') );
    
    $form->AddField( new CheckBox ( 'Checkbox 1','cb1',1, 'checado') );
    $form->AddField( new CheckBox ( 'Checkbox 2','cb2',2, null) );
    $form->AddField( new TextLabel('Informação','Um campo para exibir info.') );
    
    $form->AddField( new Separator('') );
    
    $form->AddField( new RadioButton('Pesquisar em','opt1','1',TRUE,'Todos os campos') );
    $form->AddField( new RadioButton('&nbsp;','opt1','2',false,'Código') );
    $form->AddField( new RadioButton('&nbsp;','opt1','3',false,'Descrição') );
    
    
    $form->AddField( new Separator('') );
    
    $form->AddField( new CheckBoxGroup('Operador','oper_cb',array('AND','OR','NOT')) );
    $form->AddField( new RadioButtonGroup('Operador','oper_rb',array('AND','OR','NOT')) );
        
    $form->AddField( new MultiTextField('Nomes do Alunos', 'nomes', 200, null, true) ) ;
    
    $selection = new Selection('Cidade','cidade',0,$array_frutas);
    $selection->AddAttribute('onChange','this.form.submit()');
    $form->AddField($selection);
    
    $options = array( 0 => "Daniel",
                      1 => "Vilson",
                      2 => "Mcastro",
                      3 => "Pablo",
                      4 => "Nasair");
    
    $values = array( 2 => "Mcastro",
                     4 => "Nasair");
    
    $form->AddField( new MultiChoiceField("Multi Choice", "fields", 200, $options, $values) );
    
    
    $theme->SetContent($form);
    
    //TabbedForm ...
?>
