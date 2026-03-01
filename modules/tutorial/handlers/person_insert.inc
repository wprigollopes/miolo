<?
// +-----------------------------------------------------------------+
// | MIOLO - Miolo Development Team - UNIVATES Centro Universitario  |
// +-----------------------------------------------------------------+
// | CopyLeft (L) 2002 UNIVATES, Lajeado/RS - Brasil                 |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigolivre.org.br                           |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// |                                                                 |
// | Authors: Vilson Cristiano Gartner, Thomas Spriestersbach        |
// |                                                                 |
// +-----------------------------------------------------------------+
// $Id: person_insert.inc,v 1.1 2002/09/16 18:06:05 vgartner Exp $

    // $main indica o "caminho" para o arquivo atual
    // é uma forma de simplificar (e diminuir ;-) o código
    $main = 'main:person_insert';
    
    // adicionando link ao barra de navegação
    $navbar->AddOption('Incluir Pessoa', 'tutorial', $main);
    
    // adicionando uma opção ao menu
    $menu->AddOption('Listar Pessoas', $module, 'main:person_list');
    
    // obter acesso às classes de Interface de Usuário
    $ui = $MIOLO->GetUI();
    
    // cria o formulário, definido no arquivo tutorial/forms/PessoaForm.class
    $form = $ui->GetForm($module,'PessoaForm');
    
    // GetActionURL retorna uma URL que aponta para o module e arquivo indicados
    // neste caso, aponta para o arquivo atual
    $handler = $MIOLO->GetActionURL($module, $main);
    
    // atribuir a ação(destino) dos dados do formulário
    $form->SetAction($handler);
    
    // tratamento para ser executado quando o formulário for enviado
    // IsSubmitted retorna true quando o formulário foi enviado
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
