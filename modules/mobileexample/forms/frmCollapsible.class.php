<?php

/**
 * JQuery Mobile example.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2012/08/06
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2012 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 */
class frmCollapsible extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Acordeão', MIOLO::getCurrentModule()));

        $this->eventHandler();
        $this->setShowPostButton(FALSE);
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array();
        $sections = array();

        $fields[] = MDialog::getDefaultContainer();

        $section1 = array( new MLabel(_M('Conteúdo da seção @1', $module, '1')) );
        $sections[] = new jCollapsibleSection(_M('Seção @1', $module, '1'), $section1);

        $section2 = array( new MLabel(_M('Conteúdo da seção @1', $module, '2')) );
        $sections[] = new jCollapsibleSection(_M('Seção @1', $module, '2'), $section2);

        $fields[] = new jCollapsible('acordeao', $sections);

        $this->setFields($fields);
    }
    
    /**
     * Exibe confirmação para sair do sistema. 
     */
    public function confirmarSair()
    {
        $campos = array();
        $botoes = array();

        $campos[] = new MLabel(_M('Você realmente deseja sair?', $this->modulo));
        $botoes[] = new MButton('botaoCancelarSair', _M('Não', $this->modulo), "dijit.byId('dialogoConfirmarSair').hide();");
        $botoes[] = new MButton('botaoSair', _M('Sim', $this->modulo), ':sair');
        $campos[] = MUtil::centralizedDiv($botoes);

        $dialog = new MDialog('dialogoConfirmarSair', _M('Confirmar', $this->modulo), $campos);
        $dialog->show();
    }

    /**
     * Redireciona para tela de logout. 
     */
    public function sair()
    {
        MDialog::close('dialogoConfirmarSair');
        $this->setResponse(NULL, 'divBotaoSair');
        $url = $this->manager->getActionURL($this->modulo, 'main:logout');
        $this->page->redirect($url);
    }
}

?>