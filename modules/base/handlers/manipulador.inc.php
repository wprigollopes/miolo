<?php

/**
 * Copyright 2005-2017 Solis Soluções Livres Ltda.
 *
 * This file is part of the SolisGE/Sagu program.
 *
 * SolisGE/Sagu is proprietary software of SOLIS, developed and maintained
 * exclusively by this company.
 *
 * The usage license is available through exclusive acquisition from SOLIS.
 * The license is granted on a non-exclusive basis to the licensee.
 * Usage rights are perpetual.
 *
 * Although source code is provided, the software is the property of SOLIS.
 * The licensee is not permitted to resell, lend, or transfer (whether for
 * payment or not) the license to third parties. It is also not permitted,
 * at any time or for any reason, to perform any alienation, reproduction,
 * distribution, disclosure, registration, licensing, transfer, or any other
 * act that may harm or compromise the software property rights, the name
 * and image of its owner and the software itself, or that constitutes
 * competition with SOLIS.
 *
 * The licensee, with access to the software source code, shall have the
 * right to make changes to the respective code. However, in situations
 * where the licensee relies on official support provided by SOLIS, changes
 * to the source code are not permitted, under penalty of losing said support.
 *
 * For detailed information about the SolisGE/Sagu Software Licensing Terms,
 * read the "LICENCA.txt" file included with this software.
 *
 *
 * Form handler
 *
 *
 */

// Gets the action key.
$chave = MIOLO::_REQUEST('chave');

// Gets the Miolo instance, module and action.
$MIOLO = MIOLO::getInstance();
$modulo = MIOLO::getCurrentModule();
$acao = MIOLO::getCurrentAction();

// Handles forms if a key exists.
if ( $chave )
{
    // Gets the full path to the default management form.
    /*$formulario = $this->manager->getModulePath($modulo, 'forms/frm' . ucfirst($chave) . '.class.php');

    // If no default form exists, it is step-by-step.
    if ( !file_exists($formulario) )
    {
        // Calls the handler that controls the step-by-step.
        $MIOLO->invokeHandler($modulo, $chave);
    }
    else
    {*/
        // Calls the forms.
        bManipular($chave);
    /*}*/
}

// Calls the lookup, logout or login handler.
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
 * Function to handle forms.
 *
 * @param string $chave Form key.
 */
function bManipular($chave)
{
    $MIOLO = MIOLO::getInstance();
    $modulo = MIOLO::getCurrentModule();
    
    $funcao = MIOLO::_REQUEST('funcao');
    $ui = $MIOLO->getUI();
    
    // Key description
//    $descricaoChave = ucfirst($chave);
    $descricaoChave = $chave;
    
    $parametros = array(
        'modulo' => $modulo,
        'funcao' => $funcao,
        'tipo' => $chave
    );
    
    $checked = false;
    // FIXME: add checkAccess.
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

                // Checks if the form code exists.
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

                // Checks if the form code exists.
                if ( !$formularioBusca )
                {
                    $formularioBusca = 'frmDinamicoBusca';
                    $modulo = 'base';
                }
                $MIOLO->checkAccess($formularioBusca, $access, $deny);
                // Instantiates the dynamic search form.
                $conteudo = $ui->getForm($modulo, $formularioBusca, $parametros);

                break;
        }
        $theme->setContent($conteudo);
    }
}

/**
 * Function to handle step forms.
 *
 * @todo Implement this function.
 *
 * @param string $chave Form key.
 * @param array $passos Form names.
 * @param array $passosDescricao Form titles.
 * @param string Title of the step process.
 */
function bManipularPassos($chave, $passos, $passosDescricao, $titulo)
{
    $MIOLO = MIOLO::getInstance();
    $modulo = MIOLO::getCurrentModule();
    
    $funcao = MIOLO::_REQUEST('funcao');
    $ui = $MIOLO->getUI();
    
    // Key description
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
            
            // Adds the breadcrumb.
            $barraDeNavegacao = $theme->getElement('navigation');
            $barraDeNavegacao->addOption($titulo, $modulo, 'main');
            
            $passo = MStepByStepForm::getCurrentStep();
            $formulario = $passo ? $passos[$passo] : array_shift($passos);

            MStepByStepForm::setShowImageOnButtons(true);
            $conteudo = $ui->getForm($modulo, $formulario, $passosDescricao);
            
            $action = $MIOLO->getActionURL($modulo, 'main', NULL, array( 'chave' => $chave ));
            $conteudo->controlButtons[MStepByStepForm::CANCEL_BUTTON_ID] = $conteudo->cancelButton($action);
                
            // Disables Javascript validation.
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