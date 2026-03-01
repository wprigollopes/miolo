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
// $Id: person_delete.inc,v 1.3 2004/08/23 18:29:45 vgartner Exp $

    // adicionando link a barra de navegação
    $navbar->AddOption('Excluir Pessoa', 'tutorial', 'main:person_delete');
    
    // adicionando uma opção ao menu
    $menu->AddOption('Listar Pessoas', $module, 'main:person_list');
    
    // obter acesso às classes de Interface de Usuario
    $MIOLO->GetUI();
    
    // gera uma mensagem de erro e para a execucão do script
    // caso o cpf não tenha sido informado
    $MIOLO->Assert( isset($item), 'O cpf da pessoa não foi informado.');
    
    // a variável $confirmed é recebida quando se confirma ou não
    // a exclusão da pessoa
    $confirmed = MIOLO::_Request('confirmed'); // É necessário 'pegar' a variável confirmed, caso o 'globals' do php estiver desabilitado
    if (isset($confirmed) )
    {
        if ( $confirmed == 'yes' )
        {
            // O método GetBusiness é utilizado para instanciar
            // classes db, definidas nos arquivos em ../db
            // Aqui é instancia a classe BusinessTutorialPerson, que
            // está no arquivo db/person.class do módulo tutorial
            $business = $MIOLO->GetBusiness($module,'person');

            // se a exclusão foi bem sucedida 
            if ( $business->DeletePerson($item) )
            {
                // cria um link para a listagem de pessoas
                $url = $MIOLO->GetActionURL($module,'main:person_list');
                // mostra uma mensagem e passa a url que será associado ao botão
                $theme->SetContent( Prompt::Information('Registro excluído com sucesso.', $url) );
            }
            else
            {
                // pega os erros ocorridos na execução do $sql
                $err = $business->GetErrors();
                // mostra os erros ocorridos
                $theme->SetContent( Prompt::Error( $err) );
            }
        }
        else
        {
            $url = $MIOLO->GetActionURL($module,'main:person_list');
            $theme->SetContent( Prompt::Information('Exclusao cancelada.', $url) );
        }
    }
    // $confirmed ainda não existe, isso indica que estamos vindo da listagem
    // e teremos que solicitar a confirmação ou não da exclusão
    else
    {
        // a função GetActionURL retorna uma URL que aponta para o 
        // respectivo módulo e arquivo especificados nos parâmetros. 
        // o quarto parâmetro deve ser um array, onde o key (confirmed)
        // será a variável $confirmed
        $url_yes = $MIOLO->GetActionURL($module,'main:person_delete', $item, array('confirmed'=>'yes'));
        $url_no  = $MIOLO->GetActionURL($module,'main:person_delete', $item, array('confirmed'=>'no'));
        
        // mostra uma mensagem na tela
        // a classe Prompt possui também outros métodos como Question, Error,...
        $content = Prompt::Question("Confirma a exclusão da pessoa $name, cpf= $item?", $url_yes, $url_no );
        // coloca a mensagem na página
        $theme->SetContent( $content );
    }

?>
