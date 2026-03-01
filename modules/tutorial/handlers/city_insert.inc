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
// $Id: city_insert.inc,v 1.2 2002/11/16 11:08:57 vgartner Exp $

    // adicionando link a barra de navegação
    $navbar->AddOption('Incluir Cidade', $module, 'main:city_insert');
    
    // adicionando uma opção ao menu
    $menu->AddOption('Listar Cidades', $module, 'main:city_list');
    
    // obter acesso as classes de Interface de Usuário
    $MIOLO->GetUI();

    // tratamento para ser executado quando o formulário for enviado
    // IsSubmitted retorna true quando o formulário foi enviado
    if ( Form::IsSubmitted() )
    {
        // GetDataBase: cria uma conexão com a base 'tutorial'
        // essa base deve estar configurada no arquivo miolo.conf
        $db = $MIOLO->GetDatabase('tutorial');

        // sql para inclusão da cidade.
        // As variáveis $frm_* são criadas automaticamente pelo MIOLO, que
        // acrescenta o prefixo frm_ na frente dos campos definidos no form
        $sql = "insert into tut_cities " .
	       "       ( zip_code, state, name ) " .
               "values " .
	       "       ('$frm_zip_code','$frm_state','$frm_name')";

        // executa a instrução $sql e retorna true ou false
        $ok = $db->Execute($sql);

        // se a instrução foi bem sucedida
        if ( $ok )
        {
            // cria uma URL para o arquivo cidade_insert.inc
            $url = $MIOLO->GetActionURL($module, 'main:city_insert');
            
            // mostra uma mensagem na tela
            // a classe Prompt possui também outros métodos como Question, Error,...
            $theme->SetContent( Prompt::Information('Cidade Cadastrada!', $url) );
            
            // interrompe a execução deste script e retorna o controle
            // para o script anterior (main)
	    // deve-se especificar o true, senão o main.inc interpretará como false
            return true;
        }
        
        // Pega os erros ocorridos na execução do $sql
        $err = $db->GetErrors();
        
    }

    // a função GetActionURL retorna uma URL que aponta para o
    // respectivo e arquivos específicados nos parâmetros
    $handler = $MIOLO->GetActionURL($module,'main:city_insert');
    
    // criando o formulário
    $form = new Form();
    // definir a ação (destino dos dados) do formulário
    $form->SetAction($handler);
    // título do formulário
    $form->SetTitle('Cadastro de Cidade');
    // se ocorreram erros na execução do $sql, serão exibidos no form
    $form->AddError($err);

    // definindo lista de UF's
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

    // adicionando os campos ao formulário
    // o método GetFormValue retorna o valor da váriavel passada como parâmetro
    $form->AddField(new TextField('Nome', 'name', Form::GetFormValue('name'), 40),
                    'Digite o nome da cidade');
    $form->AddField(new TextField('CEP', 'zip_code', Form::GetFormValue('zip_code'), 10,
                                  'Informe o CEP da cidade no formato 99999-999', Validator::CEPValidator()) );
    $form->AddField(new Selection('UF', 'state', Form::GetFormValue('state'), $options_uf),
                    'Selecione o estado da federacao no qual fica a cidade');
    
    // colocando o formulário como conteúdo da pagina
    $theme->SetContent($form);

?>
