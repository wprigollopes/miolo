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
// $Id: person_list.inc,v 1.6 2004/03/02 19:46:22 vgartner Exp $

/*
Exemplo de como utilizar a permissão de acesso por máquina (IP)
if (! MIOLO::IsHostAllowed() )
{
    echo "HOST SEM PERMISSÃO DE ACESSO.";
    exit;
}
else
{
    echo "host com permissão";
}
*/
    // definir o caminho para o arquivo atual
    $main = 'main:person_list';
    
    // adicionando link na barra de navegação
    $navbar->AddOption('Listar Pessoa', 'tutorial', $main);
    
    // obter acesso as classe do UI
    $ui = $MIOLO->GetUI();
    
    // instanciando um novo objeto Listing (criando uma nova listagem)
    $listing = new Listing();
    
    // definindo um título
    $listing->SetTitle('Listagem de Pessoas');
    
    // definindo os rótulos(colunas) da listagem
    $labels = array(new ListingColumn('CPF'),
                    new ListingColumn('Nome'),
                    new ListingColumn('Endereço'),
                    new ListingColumn('Bairro'),
                    new ListingColumn('CEP'),
                    new ListingColumn('Cidade'),
                    new ListingColumn('UF'),
                    new ListingColumn('Fone'),
                    new ListingColumn('Email'));
    // para não exibir uma coluna na listagem,
    // o parâmetro passado deve ser: null
    
    // 'setar' as colunas da listagem
    $listing->SetLabels($labels);
    
    // criar uma URL que aponte para a página atual
    $url = $MIOLO->GetActionURL($module, $main);
    
    // instanciando um objeto PageNavigator
    // serão mostradas 5 itens por página
    $pn = new PageNavigator(2, $url);
    
    // instanciar a classe de acesso aos dados
    // lembre-se: as classes de acessos à base estão no diretório ../db
    $business = $MIOLO->GetBusiness( $module, 'person' );
    
    // GetPersonList retorna a lista de pessoas, no intervalo de dados
    // indicado por GetQUeryRange do PageNavigator
    $pessoas = $business->GetPersonList( $pn->GetQueryRange() );
    
    // adiciona erros ocorridos à listagem
    $listing->AddError( $business->GetErrors() );
    
    // definindo o resultado da busca (objeto $pessoas) como conteúdo da listagem
    $listing->SetData( $pessoas );
    
    // definindo o PageNavigator como roda-pé da listagem
    $listing->SetFooter($pn);
    
    // criar as ações (URL) para as respectivas colunas da listagem
    $action_edit = $MIOLO->GetActionURL($module,'main:person_edit','%0%');
    $action_dele = $MIOLO->GetActionURL($module,'main:person_delete','%0%', Array('name'=>'%1%'));
    
    // definir coluna com ícone para alteração dos dados
    $listing->AddActionIcon('Editar', 'update.gif', $action_edit);
    // definir coluna com ícone para exclusão da pessoa
    $listing->AddActionIcon('Excluir', 'delete.gif', $action_dele);
    // o miolo automaticamente procura o arquivo .gif
    // primeiro tenta encontrar o mesmo no images diretório do tema e em
    // seguida, procura no diretório /images
    
    // definindo a listagem como conteúdo principal
    $theme->SetContent($listing);

?>
