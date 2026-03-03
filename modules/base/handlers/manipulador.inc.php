<?php

/**
 * Copyright 2005-2017 de Solis Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa SolisGE/Sagu.
 *
 * O SolisGE/Sagu é um software de propriedade da SOLIS, sendo desenvolvido
 * e mantido exclusivamente por esta empresa.
 *
 * A licença de uso está disponível mediante aquisição exclusiva junto à
 * SOLIS. A licença é concedida sem caráter de exclusividade ao licenciado.
 * Os direitos de uso são perpétuos.
 *
 * Embora os códigos fontes sejam fornecidos, o software é de propriedade
 * da SOLIS, não sendo permitido ao adquirente da licença a sua revenda,
 * empréstimo ou cessão (onerosa ou não) à terceiros. Também não é permitido,
 * a qualquer título e tempo, promover no software qualquer tipo de alienação,
 * reprodução, distribuição, divulgação, registro, licenciamento, transferência
 * ou qualquer outro ato que prejudique ou comprometa os direitos de propriedade
 * de software, o nome e a imagem da sua proprietária e do próprio software,
 * além de configurar concorrência à SOLIS.
 *
 * O licenciado, com o acesso ao código fonte do software, terá o direito de
 * promover mudanças no respectivo código. No entanto, nas situações em que ele
 * contar com o suporte oficial prestado pela SOLIS, não poderá promover mudanças
 * no código fonte, sob pena de perda do referido suporte.
 *
 * Para conhecer em detalhes o Termo de Licenciamento do Software SolisGE/Sagu
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software.
 *
 *
 * Manipulador de formulários
 *
 *
 */

// Obtém a chave da ação.
$chave = MIOLO::_REQUEST('chave');

// Obtém instância do Miolo, módulo e ação.
$MIOLO = MIOLO::getInstance();
$modulo = MIOLO::getCurrentModule();
$acao = MIOLO::getCurrentAction();

// Faz a manipulação de formulários caso exista uma chave.
if ( $chave )
{
    // Obtém o caminho completo para o formulário de gerênciamento padrão.
    /*$formulario = $this->manager->getModulePath($modulo, 'forms/frm' . ucfirst($chave) . '.class.php');

    // Se nao existir formulário padrão, é passo-a-passo.
    if ( !file_exists($formulario) )
    {
        // Chama o handler que controla o passo-a-passo.
        $MIOLO->invokeHandler($modulo, $chave);
    }
    else
    {*/
        // Faz a chamada dos formulários.
        bManipular($chave);
    /*}*/
}

// Chama handler do lookup, logout ou login.
if ( in_array($acao, array('lookup', 'logout', 'login') ) )
{
    $MIOLO->invokeHandler($modulo, $acao);
}

function obterCaminhoForm($modulo, $nome)
{
    $MIOLO = MIOLO::getInstance();

    $frmNome = 'frm' . $nome;
    $caminhoDir = $MIOLO->getModulePath($modulo, 'forms/' . $frmNome . '.class.php');
    
    if ( file_exists($caminhoDir)  )
    {
        return $frmNome;
    }
    else
    {
        $frmNome = 'frm' . ucfirst($nome);
        $caminhoDir = $MIOLO->getModulePath($modulo, 'forms/' . $frmNome . '.class.php');
        
        if ( file_exists($caminhoDir) )
        {
            return $frmNome;
        }
    }
    
    return false;
}

/**
 * Função para manipular formulários.
 * 
 * @param string $chave Chave do formulário.
 */
function bManipular($chave)
{
    $MIOLO = MIOLO::getInstance();
    $modulo = MIOLO::getCurrentModule();
    
    $funcao = MIOLO::_REQUEST('funcao');
    $ui = $MIOLO->getUI();
    
    // Descrição da chave
//    $descricaoChave = ucfirst($chave);
    $descricaoChave = $chave;
    
    $parametros = array(
        'modulo' => $modulo,
        'funcao' => $funcao,
        'tipo' => $chave
    );
    
    $checked = false;
    // FIXME: adicionar checkAccess.
    $theme = $MIOLO->getTheme();
    $theme->clearContent();
    
    $nomeFormulario = 'frm' . $descricaoChave;
    $perms = $MIOLO->perms;
    $perms instanceof BPermsBase;

    if ($perms->hasTransaction($nomeFormulario))
    {
        if ($MIOLO->checkAccess($nomeFormulario, $perms->converterFuncaoDaBaseParaAccess($funcao), true, true))
        {
            $checked = true;
        }
    }
    else
    {
        $checked = true;
    }
    
    if ($checked == true)
    {
        switch ($funcao)
        {
            case FUNCAO_INSERIR:
            case FUNCAO_EDITAR:
            case FUNCAO_EXPLORAR:
//                $nomeFormulario = 'frm' . $descricaoChave;
                $formularioCadastro = obterCaminhoForm($modulo, $descricaoChave);

                // Verifica se o código do formulário existe.
                if ( !$formularioCadastro )
                {
                    $formularioCadastro = 'frmDinamico';
                    $modulo = 'base';
                }

                $conteudo = $ui->getForm($modulo, $formularioCadastro, $parametros);

                break;

            case FUNCAO_BUSCAR:
            default:
//                $nomeFormulario = 'frm' . $descricaoChave . 'Busca';
                $caminho = $descricaoChave . 'Busca';
                $formularioBusca = obterCaminhoForm($modulo, $caminho);

                // Verifica se o código do formulário existe.
                if ( !$formularioBusca )
                {
                    $formularioBusca = 'frmDinamicoBusca';
                    $modulo = 'base';
                }
                $MIOLO->checkAccess($formularioBusca, $access, $deny);
                // Instância formulário de busca dinâmica.
                $conteudo = $ui->getForm($modulo, $formularioBusca, $parametros);

                break;
        }
        $theme->setContent($conteudo);
    }
}

/**
 * Função para manipular formulários de passos.
 * 
 * @todo Implementar essa função.
 * 
 * @param string $chave Chave do formulário.
 * @param array $passos Nomes dos formulários.
 * @param array $passosDescricao Títulos dos formulários.
 * @param string Título do processo de passos.
 */
function bManipularPassos($chave, $passos, $passosDescricao, $titulo)
{
    $MIOLO = MIOLO::getInstance();
    $modulo = MIOLO::getCurrentModule();
    
    $funcao = MIOLO::_REQUEST('funcao');
    $ui = $MIOLO->getUI();
    
    // Descrição da chave
//    $descricaoChave = ucfirst($chave);
    $descricaoChave = $chave;
    
    $nomeFormulario = 'frm' . $descricaoChave;
    if ($MIOLO->perms->hasTransaction($nomeFormulario))
    {
        if ($MIOLO->checkAccess($nomeFormulario, $MIOLO->perms->converterFuncaoDaBaseParaAccess($funcao), true))
        {
            $theme->clearContent();
        }
    }
    
    $theme = $MIOLO->getTheme();
    
    $formBusca = $MIOLO->getConf('home.modules') . "/$modulo/forms/frm{$descricaoChave}Busca.class.php";

    if ( !file_exists($formBusca) )
    {
        $funcao = FUNCAO_INSERIR;
    }

    switch ( $funcao )
    {
        case FUNCAO_INSERIR:
        case FUNCAO_EDITAR:
            
            // Adiciona a migalha.
            $barraDeNavegacao = $theme->getElement('navigation');
            $barraDeNavegacao->addOption($titulo, $modulo, 'main');
            
            $passo = MStepByStepForm::getCurrentStep();
            $formulario = $passo ? $passos[$passo] : array_shift($passos);

            MStepByStepForm::setShowImageOnButtons(true);
            $conteudo = $ui->getForm($modulo, $formulario, $passosDescricao);
            
            $action = $MIOLO->getActionURL($modulo, 'main', NULL, array( 'chave' => $chave ));
            $conteudo->controlButtons[MStepByStepForm::CANCEL_BUTTON_ID] = $conteudo->cancelButton($action);
                
            // Desativa validação via Javascript.
            $conteudo->setJsValidationEnabled(false);
            break;
        
        case FUNCAO_BUSCAR:
        default:
            $parametros = array(
                'modulo' => $modulo,
                'funcao' => $funcao,
                'tipo' => $chave
            );
            $conteudo =  $ui->getForm($modulo, 'frm' . $descricaoChave . 'Busca', $parametros);
            break;
    }
   
    $theme->clearContent();
    $theme->insertContent($conteudo);
}

?>