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
// $Id: city_edit.inc,v 1.2 2002/11/16 11:08:57 vgartner Exp $

    // adicionando link a barra de navegação
    $navbar->AddOption('Editar Cidade', 'tutorial', 'main:city_edit');
    
    // adicionando uma opção ao menu
    $menu->AddOption('Listar Cidades', $module, 'main:city_list');
    
    // obter acesso às classes de Interface de Usuário
    $MIOLO->GetUI();
    
    // tratamento para ser executado quando o formulario for enviado
    // IsSubmitted retorna true quando o formulário foi enviado
    if ( Form::IsSubmitted() )
    {
        // associar valores do formulário às variáveis
	// o metodo GetFormValue retorna o valor de variáveis do formulário
        $name     = Form::GetFormValue('name');
        $zip_code = Form::GetFormValue('zip_code');
        $state    = Form::GetFormValue('state');
        
        // GetDataBase: cria uma conexão com a base 'tutorial'
        // essa base deve estar configurada no arquivo miolo.conf
        $db = $MIOLO->GetDatabase('tutorial');
        
        // sql para alteração da cidade
        $sql = "update tut_cities ".
               "   set state = '$state', name = '$name'" .
               " where zip_code='$zip_code'";
        
        // Execute a instrução $sql e retorna true ou false
        $ok = $db->Execute($sql);
        
        // se a instrução foi bem sucedida
        if ( $ok )
        {
            // cria uma URL que aponta para o arquivo cidade_listar.inc
            $url = $MIOLO->GetActionURL($module, 'main:city_list');
            
            // mostra uma mensagem na tela com um um botão que aponta para a $url
            // a classe Prompt possui também outros metodos como Question, Error,...
            $theme->SetContent( Prompt::Information('Cadastro modificado!', $url) );
            
            // interrompe a execucao deste script e retorna o controle
            // para o script anterior (main)
	    // é necessário especificar true, pois caso contrário é interpretado
	    // como false no arquivo main.inc
            return true;
        }
        
        // Pega os erros ocorridos na execução do $sql
        $err = $db->GetErrors();
    }
    
    // a variável $item foi enviada pela listagem de cidades (city_list)
    if ( isset($item) )
    {
        // GetDataBase: cria uma conexão com a base 'tutorial'
        // essa base deve estar configurada no arquivo miolo.conf
        $db = $MIOLO->GetDatabase('tutorial');
        
        // sql para selecionar os dados da cidade
        $sql = " select zip_code, name, state ".
               "   from tut_cities ".
               "  where zip_code='$item'";
        
        // Query retorna os dados da consulta
        $result = $db->Query($sql);
        
        // Pega os erros ocorridos na execução do $sql
        $err = $db->GetErrors();
        
        // atribui o resultado de $result para as variáveis
        list ( $zip_code, $name, $state ) = $result[0];
    }
    
    // a função GetActionURL retorna uma URL que aponta para o 
    // respectivo módulo e arquivo, especificados nos parâmetros
    $handler = $MIOLO->GetActionURL($module,'main:city_edit');

    // criando o formulário
    $form = new Form();
    // Título do formulário
    $form->SetTitle('Alteracao de Cidade');
    // definir a ação (destino dos dados) do formulário
    $form->SetAction($handler);
    // se ocorreram erros na execução do $sql, serão exibidos no form
    $form->AddError($err);
    
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
    
    // criando os campos do formulário
    $form->AddField( new TextField('Nome','name', $name, 40,
                                   'Digite o nome da cidade') );
    // campo simples tipo input
    $form->AddField( new TextField('CEP', 'zip_code', $zip_code, 10,
                                   'Informe o CEP da cidade no formato 99999-999', Validator::CEPValidator()) );
    // campo tipo combobox
    $form->AddField( new Selection('UF', 'state', $state, $options_uf,
                                   'Selecione o estado da federacao no qual fica a cidade') );
    
    // colocando o formulario como conteúdo da pagina
    $theme->SetContent($form);
?>
