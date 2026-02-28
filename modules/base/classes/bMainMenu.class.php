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
 * Main menu component.
 *
 *
 *
 */

$MIOLO = MIOLO::getInstance();
$MIOLO->page->addScript('bMainMenu.js', 'base');
$MIOLO->uses('classes/bUtil.class.php', 'base');
$MIOLO->uses('types/AdmMioloTransaction.class.php', 'admin');
$MIOLO->uses('classes/bStatusBar.class.php', 'base');

class bMainMenu extends MDiv
{
    const ID = 'bMainMenu';
    const ID_LOGO = 'bMainMenuLogo';
    const ID_DADOS = 'bMainMenuData';
    const ID_JS = 'bMainMenuJS';
    const ID_ACAO = 'bMainMenuNavAction';
    const ID_ACESSO_RAPIDO = 'bMainMenuQuickAccess';

    private $logo;
    
    /**
     * @var array
     */
    private $dados;
    
    
    private $navegacao;
    
    /**
     * Indicates whether to use cache for the menu (useful for debug)
     * 
     * @var boolean
     */
    private $useCache = true;

    public function __construct()
    {
        parent::__construct(self::ID, '', 'm-main-menu');
        $MIOLO  = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $carregandoDiv = new MDiv('bMainMenuCarregandoDiv', _M('Aguarde...', 'basic'));
        $carregandoDiv->addStyle('display', 'none');
        $carregandoDiv->addStyle('z-index', '1000');
        $carregandoDiv->addStyle('position', 'fixed');
        $carregandoDiv->addStyle('padding', '6px 10px');
        $carregandoDiv->addStyle('color', 'white');

        $ui = $this->manager->getUI();
        $img = $ui->getImageTheme($this->manager->theme->id, 'logo-mini.png');
        $this->logo = new MImage(self::ID_LOGO, NULL, $img);
        
        $label = new MText("menuLabel", _M("MENU", $module));
        $label->addAttribute("style", "font-weight:bold; color:#fff");
        $this->logo->addAttribute("style", "margin-left:5px");
        $container = new MHContainer(self::ID_LOGO . 'HC', array($this->logo, $label));
        $logoDiv = new MDiv(self::ID_LOGO . 'Div', $container, self::ID . "Div logo");
        $logoDiv->addAttribute('onClick', "if (!bmainmenu) { loadMenu(true); } else { bmainmenu.show(); }");
        $logoDiv->addBoxStyle('background-color', '#1b66ab');
        $logoDiv->addBoxStyle('height', '23px');
        $logoDiv->addBoxStyle('padding', '0px');
        $logoDiv->addBoxStyle('line-height', '25px');

        $this->popular();
        $this->gerarNavegacao();
        
        $this->setInner(array( $carregandoDiv, $logoDiv, $this->navegacao ));
    }

    /**
     * Gets menu URL, applying specific treatments when necessary.
     *
     * @return string
     */
    public static function getMenuURL($modulo, $acao, $subPath = null)
    {
        $MIOLO = MIOLO::getInstance();
        $dispatch = null;        
        $confURL = $MIOLO->getConf('home.url'); // Ex.: http://meusagu.edu.br/sagu26/

        if ( $acao == 'logout' )
        {
            $url = $MIOLO->getActionURL('admin', $acao);
        }
        else
        {        
            if ( bUtil::isMiolo2() ) // Is old miolo version (2.0)
            {
                // Concatenates the subpath in the URL, if it exists
                $dispatch = $confURL . ( $subPath ? '/' . $subPath : null );
            }
            else // Is miolo version > 2.0
            {
                if ( strlen($subPath) > 0 )
                {
                    $dispatch = null; // Keeps the current URL
                }
                else
                {
                    // Cuts the subpath from the URL
                    $dispatch = dirname($confURL) . '/index.php'; // URL without the path
                }
            }
            
            if ( $modulo == 'pedagogico' )
            {
                $dispatch = str_replace('miolo26', 'miolo20', $dispatch);
            }

            $url = $MIOLO->getActionURL($modulo, $acao, null, null, $dispatch);
        }

        return $url;
    }

    /**
     * Populates the component with the data necessary for menu creation.
     * Stores data in the session to avoid database requests.
     */
    public function popular()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $sessao = $this->manager->getSession();
        $sessionId = $this->id . self::ID_DADOS;
        $sessionData = $sessao->getValue($sessionId);

        $filters = new stdClass();
        $filters->onlyWithPerms = true;

        // Cache was removed because there were issues with sometimes not re-executing the query        
        $dados = AdmMioloTransaction::listRecords($filters);
        if ( $dados )
        {

            // Generates logout button
            $actLogout = 'base|logout';
            $img = $MIOLO->getUI()->getImageTheme($MIOLO->theme->id, 'logout_-16x16.png');
            $dados[$actLogout] = current($dados);
            $dados[$actLogout]['modulo'] = 'base';
            $dados[$actLogout]['temFilho'] = false;
            $dados[$actLogout]['descricao'] = "<img src=\"{$img}\"></img> ". _M('Sair', $module);

            // Generates quick access options
            foreach ( $dados as $moduloAcao => $item )
            {
                // Quick access actions are not generated for sub-menus
                if ( $item['temFilho'] )
                {
                    continue;
                }

                list($modulo, $acao) = explode('|', $moduloAcao);
                $codigo = $item['codigo'];
                $pai = $item['pai'];
                
                $niveis = array();
                
                while ( $acao )
                {
                    $niveis[] = $acao;

                    $acao = explode(':', $acao);
                    end($acao);
                    unset($acao[key($acao)]);
                    $acao = implode(':', $acao);
                }

                $niveis = array_reverse($niveis);
                $descricaoAcessoRapido = array();

                foreach ( $niveis as $nivel )
                {
                    $acaoAtual = $dados["$modulo|$nivel"];

                    if ( !$acaoAtual )
                    {
                        $acaoAtual = $dados["$modulo|main:$nivel"];
                    }

                    $descricaoAcessoRapido[] = $acaoAtual['descricao'];
                }

                $dados[$moduloAcao]['acessoRapido'] = implode(' :: ', $descricaoAcessoRapido);
            }

            $sessao->setValue($sessionId, $dados);
        }
        
        $this->dados = $dados;
    }

    /**
     * Gets the JavaScript code for creating main menu items and sub-menus.
     * 
     * @return string JS code.
     */
    public function gerarItensJS()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $sessao = $this->manager->getSession();

        // Gets from session to avoid repeated processing
        //$itensJS = $sessao->getValue($this->id . self::ID_JS);

        if ( !$itensJS )
        {
            $itensJS = array();

            foreach ( (array)$this->dados as $moduloAcao => $item )
            {
                list($modulo, $acao) = explode('|', $moduloAcao);
                $codigo = $item['codigo'];
                $descricao = $item['descricao'];
                $pai = $item['pai'];
                $acessoRapido = $item['acessoRapido'];
                $subPath = $item['subpath'];

                if ( $item['temFilho'] )
                {
                    $itensJS[] = "bmainmenu.addSubMenu('{$codigo}', '$descricao', null, false, '{$pai}');";
                }
                else
                {
                    if ( strlen($pai) == 0 && substr($descricao, 0, 1) == '*' )
                    {
                        continue;
                    }
                    //$acao = $this->manager->getActionURL($modulo, $acao);
                    $acao = bMainMenu::getMenuURL($modulo, $acao, $subPath);
                    $itensJS[] = "bmainmenu.addItem('{$codigo}', '{$descricao}', '{$acao}', null, false, '{$pai}', '{$acessoRapido}');";
                }
            }

            $itensJS = implode("\n", $itensJS);
            $sessao->setValue($this->id . self::ID_JS, $itensJS);
        }

        return $itensJS;
    }

    /**
     * @return string Generates navigation via sub-menus.
     */
    public function gerarNavegacao()
    {
        $acoes = array();
        $chaveMode = false;
        $modulo = $this->manager->getCurrentModule();
        $acao = $acaoPrincipal = $this->manager->getCurrentAction();
        $args = $this->manager->getCurrentURL();
        
        $args = explode("&amp;", $args);        
        $arg  = explode("=", $args[2]);
        if ( ( $arg[0] == "reportid" ) || ( $arg[0] == "report" ) )
        {
            $args = "&" . $args[2];  
        }
        elseif (($acao == 'action=main') && (strlen(MIOLO::_REQUEST('chave'))>0))
        {
            $chaveMode = true;
            $chaveData = MIOLO::_REQUEST('chave');
            $args = '&chave='.$chaveData;
        }
        else
        {
            $args = null;
        }
        $acao .= $args;
        $acaoPrincipal .= $args;
        if ( $acao )
        {
            if ($chaveMode)
            {
                $niveis[] = $acao;
                $niveis[] = 'main';
            }
            else
            {
                while ( $acao )
                {
                    $niveis[] = $acao;

                    $acao = explode(':', $acao);
                    end($acao);
                    unset($acao[key($acao)]);
                    $acao = implode(':', $acao);
                }
            }
            
            $niveis = array_reverse($niveis);
            $primeiro = false;
            
            foreach ( $niveis as $nivel )
            {
                $acaoAtual = $this->dados["$modulo|$nivel"];
                if ( !$acaoAtual )
                {
                    $acaoAtual = $this->dados["$modulo|main:$nivel"];
                }
                // Support for actions with chave= in the miolo_transaction xml
                if ( !$acaoAtual )
                {
                    $acaoAtual = $this->dados["$modulo|$nivel&chave=" . MIOLO::_REQUEST('chave')];
                }
                
                $divSeparator = new MDiv(NULL, '::', 'm-main-menu-navbar-separator');

                if ( $acaoPrincipal != $nivel && $acaoPrincipal != "main:$nivel" && isset($acaoAtual['descricao']) || $nivel == "main" )
                {
                    $id = self::ID_ACAO . $acaoAtual['codigo'];
                    $acao = new MDiv($id, $acaoAtual['descricao'], 'm-main-menu-navbar-item m-main-menu-navbar-item-clickable');
                    $acao->addAttribute('onclick', "if (!bmainmenu) { loadMenu(true, '{$id}', '{$acaoAtual['codigo']}'); } else { bmainmenu.show('{$id}', '{$acaoAtual['codigo']}'); }");

                    $url = $this->manager->getActionURL($modulo, $nivel);
                    $acao->addAttribute('ondblclick', "GotoURL'$url';");
                    
                    if ( !$primeiro )
                    {
                        $acao->addAttribute("style", "margin-left:35px");
                        $acoes[] = $acao;
                        $primeiro = true;
                    }
                    else
                    {
                        $acoes[] = $divSeparator;
                        $acoes[] = $acao;
                    }
                }
                else if ( isset($acaoAtual['descricao']) )
                {
                    $acoes[] = $divSeparator;
                    $acoes[] = new MDiv($id, $acaoAtual['descricao'], 'm-main-menu-navbar-item');
                }
            }
        }

        if ( count($acoes) == 0 )
        {
            $acoes = '&nbsp;';
        }

        $this->navegacao = new MDiv(NULL, $acoes, 'm-main-menu-navbar');
    }

    /**
     * @return string Generates quick access feature.
     */
    public function gerarAcessoRapido()
    {
        $fields = array();

        $MIOLO = MIOLO::getInstance();
        $modulo = $MIOLO->getCurrentModule();

        $url = $this->manager->getActionURL($modulo, 'main');

        if ($modulo == SModules::MODULE_AVINST)
        {
            $url = str_replace('miolo26', 'miolo20', $url);
        }

        $imagem = $this->manager->getUI()->getImageTheme($this->manager->theme->id, 'home.png');        
        $home = new MImageLink(NULL, '', "javascript:document.location.assign('$url');", $imagem);
        $home->addAttribute('title', _M('Ir para a página inicial'));

        $label = new MLabel(_M('Acesso rápido'));
        $label->setClass('m-label');

        $acessoRapido = new MTextField(self::ID_ACESSO_RAPIDO, _M('O que você deseja procurar?'), NULL, 30);
        $acessoRapido->addAttribute('onkeyup', "if (!bmainmenu) { loadMenu(false); setTimeout(function() { bmainmenu.quickaccess(event, '" . self::ID_ACESSO_RAPIDO . "'); }, 0); } else { bmainmenu.quickaccess(event, '" . self::ID_ACESSO_RAPIDO . "'); }");
        $acessoRapido->addAttribute('onClick', 'document.getElementById(\'bMainMenuQuickAccess\').value = \'\';');
        $acessoRapido->addAttribute('onBlur', 'backWhat();');
        $acessoRapido->addAttribute('onkeypress', 'return handleEnter(this, event);');
        $acessoRapido->addAttribute('style', 'color: #999');
        $acessoRapido->addAttribute("onFocus", "this.style.color = '#222';");        
        
        $this->page->addJsCode('
            function backWhat()
            {
                var value = document.getElementById(\'bMainMenuQuickAccess\').value;
                if( value == \'\' )
                {
                    document.getElementById(\'bMainMenuQuickAccess\').value = "O que você deseja procurar?";
                    document.getElementById(\'bMainMenuQuickAccess\').style.color = "#999";
                }
            } 
        ');
        
        $this->page->AddJsCode("
            function handleEnter(field, event) 
            {
                var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if ( keyCode == 13 ) 
                {                
                    var i;
                    for ( i = 0; i < field.form.elements.length; i++ )
                    {
                        if ( field == field.form.elements[i] )
                        {
                            break;
                        }
                    }
                    i = (i + 1) % field.form.elements.length;
                    field.form.elements[i-1].focus();
                    return false;
        } 
        else
                {
                    return true;
                }
            } 
        ");

        $fields[] = new MSpan(NULL, $home);
        $fields[] = $label;
        $fields[] = new MSpan(NULL, $acessoRapido);
        
        $div = new MDiv(NULL, $fields, 'm-main-menu-quickaccess');

        return $div->generate();
    }

    /**
     * @return string Generates the main menu and its features.
     */
    public function generate()
    {
        $itensJS = $this->gerarItensJS();
        $targetId = $this->logo->id . "Div";

        $onload = <<<JS
window.loadMenu = function (show, actionNodeId, id) {
    if ( !actionNodeId )
    {
        dojo.style('bMainMenuLogoDiv', 'visibility', 'hidden');
        dojo.style('bMainMenuCarregandoDiv', 'display', 'block');
    }
    setTimeout(function() {
        dojo.byId("bMainMenuLogoDiv").setAttribute('onmouseover', '');
        bmainmenu = new bMainMenu();
        bmainmenu.setup('$this->id', '{$targetId}');
        $itensJS
        bmainmenu.startup();
        if ( show )
        {
            if ( actionNodeId )
            {
                bmainmenu.show(actionNodeId, id);
            }
            else
            {
                bmainmenu.show();
            }
        }
        if ( !actionNodeId )
        {
            dojo.style('bMainMenuLogoDiv', 'visibility', 'visible');
            dojo.style('bMainMenuCarregandoDiv', 'display', 'none');
        }
    }, 0);
}
JS;
        $this->page->onload($onload);

        return parent::generate() . $this->gerarAcessoRapido();
    }
}

?>
