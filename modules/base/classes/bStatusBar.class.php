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
 * Componente de barra de status.
 *
 *
 *
 */

$MIOLO = MIOLO::getInstance();
$MIOLO->page->addScript('bMainMenu.js', 'base');

class bStatusBar extends MDiv
{
    /**
     * @var array Dados a serem exibidos na direita da barra de status.
     */
    public $cols;

    /**
     * @var MDiv Div do botão do menu de favoritos.
     */
    public $bookmarks;

    /**
     * @var MDiv Div do botão do menu de mais acessados.
     */
    public $mostAccessed;

    /**
     *
     * @var MDiv Div do bot?o do menu de unidades.
     */
    public $unidades;

    /**
     * @var boolean Indica se usuário está autenticado.
     */
    public $authenticated = false;

    /**
     * Construtor do componente de barra de status.
     *
     * @param array $cols Dados a serem exibidos na direito da barra de status.
     */
    public function __construct($cols = null)
    {
        parent::__construct(NULL, NULL, 'm-statusbar');
//        $this->addStyleFile('m_themeelement.css');
        $this->cols = $cols;

        $login = $this->manager->getLogin();

        if ( $login->id )
        {
            $this->authenticated = true;
            
            $online = (time() - $login->time) / 60;

            if ( strlen($_SESSION['loginSuperUserId']->id) > 0 )
            {
                $person = SAGU::obtemNomeDoUsuario($_SESSION['loginSuperUserId']->id) . ' (' . $_SESSION['loginSuperUserId']->id .
                    ') acessando como <b>(' . $login->id  . ")</b>";
            }
            else
            {
                $person = SAGU::obtemNomeDoUsuario() . ' (' . $login->id . ')';
            }

            $this->addInfo(_M('Usuário') . ': ' . $person);
            $this->addInfo(_M('Ativo desde') . ': ' . Date('H:i', $login->time));
            $this->addInfo(_M('Data') . ': ' . Date('d/m/Y', $login->time));
            $this->addInfo(_M('Versão') . ': ' . $this->getSaguVersion());

            $this->bookmarks = new MDiv('sBookmarksButton', _M('Favoritos'), 'm-bookmark-statusbar');
            $this->bookmarks->addAttribute('onclick', 'sbookmarksmenu.show();');
            
            $this->mostAccessed = new MDiv('sMostAccessedButton', _M('Mais Acessados'), 'm-mostaccessed-statusbar');
            $this->mostAccessed->addAttribute('onclick', 'smostaccessedmenu.show();');

            if ( sMultiUnidade::estaHabilitada() )
            {
                $this->unidades = new MDiv('sUnidadesButton', _M('Unidade') . ': ' . SAGU::NVL(sMultiUnidade::obterObjetoUnidade()->description, '-'), 'm-unit-statusbar m-status-action');
                $this->unidades->addAttribute('onclick', 'sunidadesmenu.show();');
            }
        }
    }

    /**
     * Gera o código JavaScript para a criação dos itens no menu de favoritos.
     *
     * @return string Código JavaScript para a criação dos itens.
     */
    public function createBookmarkMenu()
    {
        $code = '';

        $ui = $this->manager->getUI();
        $login = $this->manager->getLogin();
        
        $data->login = $login->id;

        $busAccess = $this->manager->getBusiness('base', 'BusAccess');
        $busModule = $this->manager->getBusiness('base', 'module');


        // Bookmark
        if ( MIOLO::_request('event') == 'resetBookmark' )
        {
            $busAccess->deleteAccess($data->login, NULL, true);
        }
        else
        {
            // FIXME: verificar necessidade de filtro por módulo
            // Caso esteja na tela principal não filtra por módulo
            /*if ( $module != 'sagu2' )
            {
                $data->moduleAccess = $module;
            }*/

            $data->isBookmark = true;
            $bookmarks = $busAccess->searchAccess($data);
        }

        if ( count($bookmarks)>0 )
        {

            foreach ( $bookmarks as $bookmark )
            {
                list($login, $module, $label, $icon, $action, $count) = $bookmark;

                $busModule->getById($module);
                $moduleName = strlen($busModule->nome) > 0 ? $busModule->nome : $module;

                // FIXME: código atual só aceita classes CSS
                // Caso não exista o ícone, exibe o default-16x16.png
                if ( !file_exists($this->manager->GetModulePath($module, null) . 'html/images/' . $icon) )
                {
                    $icon = 'default-16x16.png';
                }

                $image = $ui->getImage($id, $ico);

                $label = _M($label);
                $url = $this->manager->getActionURL($module, $action);
                $code .= "sbookmarksmenu.addItem('$action', '$label', '$url', 'm-bookmark-image');";
//                            var_dump($code);
//                            var_dump('aqui?');
            }
        }

		return $code;
    }

    /**
     * Gera código JavaScript para a criação dos itens do menu de mais acessados.
     *
     * @return type 
     */
    public function createMostAccessedMenu()
    {
        $code = '';

        $ui = $this->manager->getUI();
        $login = $this->manager->getLogin();

        $data->login = $login->id;

        $busAccess = $this->manager->getBusiness('base', 'BusAccess');
        $busModule = $this->manager->getBusiness('base', 'module');

        // Visited
        if ( MIOLO::_request('event') == 'reset' )
        {
            $busAccess->deleteAccess($data->login);
        }
        else
        {
            // FIXME: verificar necessidade de filtro por módulo
            // Caso esteja na tela principal não filtra por módulo
            /*if ( $module != 'sagu2' )
            {
                $data->moduleAccess = $module;
            }*/
        
            $data->isBookmark = false;
            $links = $busAccess->searchAccess($data);

            foreach ( (array)$links as $link )
            {
                list($login, $module, $label, $icon, $action, $count) = $link;

                $busModule->getById($module);
                $moduleName = strlen($busModule->nome) > 0 ? $busModule->nome : $module;

                // FIXME: código atual só aceita classes CSS
                // Caso não exista o ícone, exibe o default-16x16.png
                if ( !file_exists($this->manager->GetModulePath($module, null) . 'html/images/' . $icon) )
                {
                    $icon = 'default-16x16.png';
                }

                $label = _M($label);
                $url = $this->manager->getActionURL($module, $action);
                $code .= "smostaccessedmenu.addItem('$action', '$label', '".str_replace("'",'',$url)."', 'm-mostaccessed-image');";
            }
        }

        return $code;
    }

    public function createUnidadesMenu()
    {
        $module   = 'sagu2';
        $action   = 'main';
        $unidades = sMultiUnidade::obterUnidadesPessoaLogada();
        $code     = '';

        foreach ( $unidades as $unitId => $unidade )
        {
            // Somente adiciona as unidades diferentes da atual logada para op??o de troca.
            if ( $unitId != sMultiUnidade::obterUnidadeLogada() )
            {
                $url = $this->manager->getActionURL($module, $action . '&event=trocarUnidade&unitId=' . $unitId);
                $code .= "sunidadesmenu.addItem('$action', 'Unidade:$unidade', '$url', 'm-mostaccessed-image');";
            }
        }

        return $code;
    }

    public function addInfo($info)
    {
        $span = new MSpan('', $info, NULL);
        $this->cols[] = $span;
    }

    public function clear()
    {
        unset($this->cols);
    }

    public function generate()
    {
        if ( $this->authenticated )
        {
            $divLeft = new MDiv(NULL, array( $this->bookmarks, $this->mostAccessed, $this->unidades ), 'm-statusbar-left');
            $divRight = new MDiv(NULL, $this->cols, 'm-statusbar-right');
            $this->setInner(new MDiv(NULL, array($divLeft, $divRight)));

            $bookmarksJS = $this->createBookmarkMenu();
            $mostAccessedJS = $this->createMostAccessedMenu();
            $unidadesJS = $this->createUnidadesMenu();

            $module = MIOLO::getCurrentModule();
            $action = MIOLO::getCurrentAction();
            $bookmarkURL = $this->manager->getActionURL($module, $action, null, array('function' => 'search', 'event' => 'bookmark'));


            $onload = <<<JS
dojo.ready(function(){
    sbookmarksmenu = new bMainMenu();
    sbookmarksmenu.setup('sBookmarksMenu', 'sBookmarksButton');
    $bookmarksJS
    sbookmarksmenu.addChild(new dijit.MenuSeparator());
    sbookmarksmenu.addChild(new dijit.MenuItem({
        label:'Adicionar/Remover', 
        onClick:function() {
            GotoURL('$bookmarkURL'.replace(/&amp;/g,"&"));
        },
        iconClass:"m-bookmark-add-remove"
    }));

    smostaccessedmenu = new bMainMenu();
    smostaccessedmenu.setup('sMostAccessedMenu', 'sMostAccessedButton');
    $mostAccessedJS
    
    sunidadesmenu = new bMainMenu();
    sunidadesmenu.setup('sUnidadesMenu', 'sUnidadesButton');
    $unidadesJS
});
JS;
            $this->page->onload($onload);
        }

        return parent::generate();
    }
    
    public function getSaguVersion()
    {
        $MIOLO = MIOLO::getInstance();        
        $module = 'basic';
        
        if(file_exists($MIOLO->getModulePath($module, "VERSION")))
        {


            $version = file($MIOLO->getModulePath($module, "VERSION"));                
            $v = explode('.', $version[0]);

            return trim($v[0] . '.' . $v[1] . ' ( ' . $v[2] . ' )');
        }
        else
        {
            return 'Versão não encontrada';
        }    
         
    }
}

?>
