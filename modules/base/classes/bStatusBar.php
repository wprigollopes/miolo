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
 * Status bar component.
 *
 *
 *
 */

$MIOLO = MIOLO::getInstance();
$MIOLO->page->addScript('bMainMenu.js', 'base');

class bStatusBar extends MDiv
{
    /**
     * @var array Data to be displayed on the right side of the status bar.
     */
    public $cols;

    /**
     * @var MDiv Div of the bookmarks menu button.
     */
    public $bookmarks;

    /**
     * @var MDiv Div of the most accessed menu button.
     */
    public $mostAccessed;

    /**
     *
     * @var MDiv Div do bot?o do menu de unidades.
     */
    public $unidades;

    /**
     * @var boolean Indicates whether the user is authenticated.
     */
    public $authenticated = false;

    /**
     * Status bar component constructor.
     *
     * @param array $cols Data to be displayed on the right side of the status bar.
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
     * Generates the JavaScript code for creating items in the bookmarks menu.
     *
     * @return string JavaScript code for creating the items.
     */
    public function createBookmarkMenu()
    {
        $code = '';

        $ui = $this->manager->getUI();
        $login = $this->manager->getLogin();

        $data = new stdClass();
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
            // FIXME: check the need for module filtering
            // If on the main screen, do not filter by module
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

                // FIXME: current code only accepts CSS classes
                // If the icon does not exist, display default-16x16.png
                if ( !file_exists($this->manager->GetModulePath($module, null) . 'html/images/' . $icon) )
                {
                    $icon = 'default-16x16.png';
                }

                $image = $ui->getImage($module, $icon);

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
     * Generates JavaScript code for creating items in the most accessed menu.
     *
     * @return type 
     */
    public function createMostAccessedMenu()
    {
        $code = '';

        $ui = $this->manager->getUI();
        $login = $this->manager->getLogin();

        $data = new stdClass();
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
            // FIXME: check the need for module filtering
            // If on the main screen, do not filter by module
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

                // FIXME: current code only accepts CSS classes
                // If the icon does not exist, display default-16x16.png
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
            // Only adds units different from the currently logged in unit as a switch option.
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
            return 'Version not found';
        }    
         
    }
}
