<?
    $navbar->AddOption(_M('Lookup example',$module),$module,'lookup');
    $home = 'main';

    $handler = $MIOLO->GetActionURL($module,"$home:lookup");

    $ui = $MIOLO->GetUI();

    $form = $ui->GetForm($module,'LookupForm');
    $form->SetAction($handler);

    if ( Form::IsSubmitted() )
    {
	    // o método GetData retorna os dados do formulário
        $data = $form->GetData();
        
        if ( $data )
        {
            // O método GetBusiness é utilizado para instanciar
            // classes db, definidas nos arquivos em ../db
            // Aqui é instancia a classe BusinessTutorialPerson, que
            // está no arquivo db/person.class do módulo tutorial
            $business = $MIOLO->GetBusiness($module,'person');
            
            // executa o método InsertPessoa da classe BusinessTutorialPerson
            if ( $business->InsertPerson($data) )
            {
                // GetActionURL retorna uma URL que aponta para o módulo e arquivo indicados
                $url = $MIOLO->GetActionURL($module, 'main:person_list');
                
                // exibe uma mensagem, com botão que aponta para para a $url
                $theme->SetContent( Prompt::Information('Pessoa Cadastrada!',$url) );
                
                // devolve o controle para o handler anterior (main).
                // como main.inc espera por true ou false, vamos retornar true
                // de forma explícita, pois se não for informado nada, interpreta como false
                return true;
            }
            
            // adiciona ao formulário, eventuais erros ocorridos no $business
            $form->AddError( $business->GetErrors() );
        }
    }
    
    // coloca o formulário como conteúdo da página
    $theme->SetContent($form);

?>
