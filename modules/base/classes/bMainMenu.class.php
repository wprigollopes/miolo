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
 * Componente de menu principal.
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
     * Indica se deve utilizar cache para o menu (util para debug)
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
     * Obtem URL de menu, fazendo tratamentos especificos quando necessários.
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
            if ( bUtil::isMiolo2() ) // É versao antiga do miolo (2.0)
            {
                // Concatena o subpath na URL, caso exista
                $dispatch = $confURL . ( $subPath ? '/' . $subPath : null );
            }
            else // É versao do miolo > 2.0
            {
                if ( strlen($subPath) > 0 )
                {
                    $dispatch = null; // Mantem a URL atual
                }
                else
                {
                    // Corta o subpath da URL
                    $dispatch = dirname($confURL) . '/index.php'; // URL sem o path
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
     * Popula o componente com os dados necessários para a criação do menu.
     * Armazena os dados na sessão para evitar requisições a base de dados.
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

        // Foi removido o cache devido a estar havendo problemas de as vezes nao refazer a consulta        
        $dados = AdmMioloTransaction::listRecords($filters);
        if ( $dados )
        {

            // Gera botao de logout
            $actLogout = 'base|logout';
            $img = $MIOLO->getUI()->getImageTheme($MIOLO->theme->id, 'logout_-16x16.png');
            $dados[$actLogout] = current($dados);
            $dados[$actLogout]['modulo'] = 'base';
            $dados[$actLogout]['temFilho'] = false;
            $dados[$actLogout]['descricao'] = "<img src=\"{$img}\"></img> ". _M('Sair', $module);

            // Gera opções do acesso rápido
            foreach ( $dados as $moduloAcao => $item )
            {
                // Ações do acesso rápido não são geradas para sub-menus
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
     * Obtém o código JavaScript para a criação dos itens e sub-menus do menu principal.
     * 
     * @return string Código JS.
     */
    public function gerarItensJS()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $sessao = $this->manager->getSession();

        // Obtém da sessão para evitar processamento repetido
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
     * @return string Gera navegação via sub-menus.
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
                // Suporte a acoes com chave= no xml miolo_transaction
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
     * @return string Gera recurso de acesso rápido.
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
     * @return string Gera o menu principal e seus recursos.
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
