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
// $Id: city_delete.inc,v 1.3 2003/10/22 18:59:45 vgartner Exp $

    // adicionando link a barra de navegação
    $navbar->AddOption('Excluir Cidade', 'tutorial', 'main:city_delete');
    
    // adicionando uma opção ao menu
    $menu->AddOption('Listar Cidades', $module, 'main:city_list');
    
    // obter acesso às classes de Interface de Usuário
    $MIOLO->GetUI();
    
    $confirmed = $_REQUEST['confirmed']; // MIOLO::_Request('confirmed');
    
    // a variável $confirmed é recebida quando o arquivo, após
    // confirmar ou não a exclusão
    if (isset($confirmed) )
    {
        if ( $confirmed == 'yes' )
        {
            // GetDataBase: cria uma conexão com a base 'tutorial'
            // essa base deve estar configurada no arquivo miolo.conf
            $db = $MIOLO->GetDatabase('tutorial');
            
            // sql para excluir a cidade
            $sql = " delete from tut_cities where zip_code='$item'";
            
            // Executa o sql
            $result = $db->Execute($sql);
            
            // Pega os erros ocorridos na execução do $sql
            $err = $db->GetErrors();
            
            if ( $err )
            {
                // se ocorreram erros na execução do $sql, os mesmos serão exibidos
                $theme->SetContent( Prompt::Error( $err) );
            }
            else
            {
                // cria uma URL que será associada ao botão da caixa de mensagem
                $url = $MIOLO->GetActionURL($module,'main:city_list');
                // mostra uma mensagem na tela, com um botão que abre a $url
                $theme->SetContent( Prompt::Information('Registro excluído com sucesso.', $url) );
            }

        }
        else if ( $confirmed == 'no' )
        {
            $url = $MIOLO->GetActionURL($module,'main:city_list');
            $theme->SetContent( Prompt::Information('Exclusão cancelada.', $url) );
        }
    }
    // a variavel $item foi enviada pela listagem (city_list)
    else if ( isset($item) )
    {
        // a funcao GetActionURL retorna uma URL que aponta para o 
        // respectivo e arquivos especificados nos parametros. 
        // o quarto parametro deve ser um array, onde o key (confirmed)
        // sera a variavel $confirmed
        $url_yes = $MIOLO->GetActionURL($module,'main:city_delete', $item, array('confirmed'=>'yes'));
        $url_no  = $MIOLO->GetActionURL($module,'main:city_delete', $item, array('confirmed'=>'no'));
        
        
        $name = MIOLO::_REQUEST('name');
        //$vars = MIOLO::_REQUEST(array('name'));
        echo $vars['name'];
        
        // mostra uma mensagem na tela
        // a classe Prompt possui tambem outros metodos como Question, Error,...        
        $theme->SetContent( Prompt::Question("Confirma exclusão da cidade $name, CEP $item?", $url_yes, $url_no ) );
    }

?>
