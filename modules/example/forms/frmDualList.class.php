<?php

/**
 * Dual list example.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/10/19
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 */
class frmDualList extends controlForm
{
    public function __construct()
    {   
        parent::__construct(_M('Dual List', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = MMessage::getMessageContainer();

        $options = array(
            'FFFFFF' => _M('White', $module),
            '000000' => _M('Black', $module),
            'FF0000' => _M('Red', $module),
            'FFFF00' => _M('Yellow', $module),
            '0000FF' => _M('Blue', $module),
            '00FF00' => _M('Green', $module)
        );

        $value = array(
            '00FF00',
            'FFFF00',
            '000000',
        );

        $fields[] = new MDualList('colors', $value, _M('Colors', $module), $options);

        $this->addFields($fields);
    }
}

?>