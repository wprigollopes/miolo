<?php

/**
 * MSubDetail example
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/03/21
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

class frmSubDetail extends MForm
{
    public static $doEventHandler;

    /**
     * Form constructor
     */
    public function __construct()
    {
        parent::__construct(_M('SubDetail', MIOLO::getCurrentModule()));

        if ( !self::$doEventHandler )
        {
            $this->eventHandler();
            self::$doEventHandler = true;
        }

        if ( MUtil::isFirstAccessToForm() )
        {
            MSubDetail::clearData('things');
        }
    }

    /**
     * Create form fields
     */
    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $fields[] = MMessage::getMessageContainer();

        $sFields[] = new MTextField('identifier', NULL, _M('Id', $module));
        $sFields[] = new MTextField('description', NULL, _M('Description', $module), 50);
        $sFields[] = new MMultiLineField('comment', NULL, _M('Comment', $module), NULL, 4, 50);

        $columns[] = new MGridColumn(_M('Id', $module), 'left', true, NULL, true, 'identifier');
        $columns[] = new MGridColumn(_M('Description', $module), 'left', true, NULL, true, 'description');
        $columns[] = new MGridColumn(_M('Comment', $module), 'left', true, NULL, true, 'comment');

        $fields['things'] = new MSubDetail('things', _M('Things', $module), $columns, $sFields);

        $sValids[] = new MRequiredValidator('identifier');
        $sValids[] = new MRequiredValidator('description');
        $fields['things']->setValidators($sValids);

        $this->setFields($fields);
    }

    /**
     * Submit button action
     */
    public function submit_button_click($args)
    {
        $data = $args;
        $data->things = MSubDetail::getData('things');

        new MMessage(str_replace("\n", '<br/>', print_r($data->things, 1)));
    }
}
