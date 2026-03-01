<?php

/**
 * Unit test class for "browser" table
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/03/14
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

include_once '../../../classes/tests/munittest.class.php';
$MIOLO->uses('classes/browser.class.php', 'example');

class tstBrowser extends MUnitTest
{
    /**
     * Configure unit test attributes
     */
    protected function setUp()
    {
        parent::setUp();

        // defines table primary keys
        $this->pkeys = array('identifier');

        $data = new stdClass();
        $data->description = 'firefox';
        $this->addBusiness(new BusinessExampleBrowser($data));
    }
}
