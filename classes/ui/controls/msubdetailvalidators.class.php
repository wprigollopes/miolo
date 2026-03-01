<?php
/**
 * Function created to store specific validators for the MIOLO2.6 subdetail
 *
 * @author Luís Felipe Wermann [moises@solis.coop.br]
 *
 * \b Maintainers: \n
 * Luís Felipe Wermann [moises@solis.coop.br]
 *
 * @since
 * Class created on 01/06/2016
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Solucoes Livres \n
 * The Gnuteca3 Development Team
 *
 * \b Copyright: \n
 * Copyright (c) 2009-2016 SOLIS - Cooperativa de Solucoes Livres \n
 *
 * \b License: \n
 * Licensed under GPL (for further details read the COPYING file or http://www.gnu.org/copyleft/gpl.html )
 *
 * \b History: \n
 * See history in SVN repository: http://gnuteca.solis.coop.br
 *
 * */

class MSubDetailUniqueValidator extends MValidator
{

    /**
     * Construct a MSubDetailUniqueValidator
     *
     * @param string $field the id of the field to validate
     * @param string $label the label of validator
     * @param string $type the type of validator
     * @param string $msgerr the error message to show
     */
    function __construct($field, $label=null, $type='optional', $msgerr=null)
    {
        parent::__construct();
        $this->id = 'unique';
        $this->field = $field;
        $this->label = $label;
        $this->mask = '';
        $this->type = $type;
        $this->checker = 'unique';
        //$this->min      = 0;
        //$this->max      = $max;
        $this->chars = 'ALL';
        $this->msgerr = $msgerr;
    }

}
