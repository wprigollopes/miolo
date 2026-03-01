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
// $Id: person_edit.inc,v 1.2 2002/10/29 19:40:35 vgartner Exp $

    // $main indica o "caminho" para o arquivo atual
    // é uma forma de simplificar (e diminuir ;-) o código
    $main = 'main:person_edit';
    
    // adicionando link ao barra de navegação
    $navbar->AddOption('Editar Pessoa', 'exampoles', $main);
    
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
    
    // O método GetBusiness é utilizado para instanciar
    // classes db, definidas nos arquivos em ../db
    // Aqui é instancia a classe BusinessTutorialPerson, que
    // está no arquivo db/person.class do módulo tutorial
    $business = $MIOLO->GetBusiness($module,'person');
    
    // tratamento para ser executando quanto o formulário é enviado
    // IsSubmitted retorna true quando o formulário foi enviado
    if ( Form::IsSubmitted() )
    {
	// GetData retorna os dados dos campos do formulário
        $data = $form->GetData();
        
        if ( $data )
        {
            // Executa o método UpdatePessoa da classe BusinessTutorialPerson
            if ( $business->UpdatePerson($data) )
            {
                $url = $MIOLO->GetActionURL($module, 'main:person_list');
                
		        $content = Prompt::Information('Os dados da Pessoa foram modificados!', $url);
                
                $theme->SetContent( $content );
                
                return true;
            }
        }
    }
    else
    {
        // gera uma mensagem de erro caso o cpf não tenha sido informado
        $MIOLO->Assert( isset($item), 'O CPF da pessoa não foi informado!');
        
        // executa o método GetPerson, da classe BusinessTutorialPerson
        $person = $business->GetPerson($item);
        // coloca os dados da pessoa no formulário
        $form->SetData($person);
    }

    // adiciona ao formulário eventuais erros ocorridos no $business
    $form->AddError( $business->GetErrors() );
    
    // colocando o formulário como conteúdo principal da página
    $theme->SetContent($form);
?>
