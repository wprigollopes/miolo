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
// $Id: city_list.inc,v 1.2 2003/10/22 18:59:45 vgartner Exp $

    // adicionando link a barra de navegação
    $navbar->AddOption('Listar Cidades', 'tutorial', 'main:city_list');
    
    // obter acesso às classes de Interface de Usuário
    $ui = $MIOLO->GetUI();
    
    // instanciando um novo objeto Listing (criando uma listagem)
    $listing = new Listing();
    // definindo título da listagem
    $listing->SetTitle('Listagem de Cidades');
    
    // criando rótulos (colunas), especificando alinhamento e tamanho
    $labels = array(new ListingColumn('CEP',null,'left',true,60),
                    new ListingColumn('Nome',null,'left',true,'100%'),
                    new ListingColumn('UF',null,'left',true,20));
    
    // inserindo as colunas na listagem
    $listing->SetLabels($labels);
    
    // obtendo acesso ao BD tutorial
    $db = $MIOLO->GetDatabase('tutorial');
    
    // comando SQL para consulta de cidades
    $sql = ' select zip_code, name, state ' .
           '   from tut_cities '.
           '  order by name';
    
    // buscando a lista das cidades cadastrados
    $result = $db->Query($sql);
    
    // se ocorreu algum erro, será colocado na listagem
    $listing->AddError( $db->GetErrors() );
    
    // colocando resultado da consulta $sql na listagem
    $listing->SetData($result);
    
    // criar as ações (URL) para as respectivas colunas da listagem
    $city_edit = $MIOLO->GetActionURL($module, 'main:city_edit', '%0%', Array('name'=>'%1%'));
    $city_dele = $MIOLO->GetActionURL($module, 'main:city_delete', '%0%', Array('name'=>'%1%'));
    
    // definir uma coluna com link texto para alteração dos dados
    // observe que %0% retorna a primeira coluna da linha de dados
    $listing->AddActionText('','EDITAR', $city_edit);
    
    // definir uma coluna com link imagem para exclusão dos dados.
    // observe que %0% retorna a primeira coluna da linha de dados
    $listing->AddActionIcon('EXCLUIR','delete.gif', $city_dele);
    
    // colocando listagem como conteúdo principal da página
    $theme->SetContent($listing);
?>
