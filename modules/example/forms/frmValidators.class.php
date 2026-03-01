<?php

/**
 * Validator examples.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/08/05
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 */

class frmValidators extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Validators Sample', MIOLO::getCurrentModule()));

        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));

        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = MMessage::getMessageContainer();

        $validation = MIOLO::_REQUEST('currentJsValidation');

        if ( $validation )
        {
            $this->setJsValidationEnabled($validation);
        }
        else
        {
            $validation = $this->getJsValidationEnabled();
        }

        $fields[] = new MDiv('divTitle', $this->getValidationTitleDiv($validation));
        $fields[] = new MSeparator();

        $fields[] = new MTextField('requiredField', '', _M('Required', $module), 20);
        $valids[] = new MRequiredValidator('requiredField');

        $fields[] = new MTextField('masked', '12ab34cd', _M('Mask', $module), 20, '99aa99aa');
        $valids[] = new MMaskValidator('masked', '', '99aa99aa', 'required');

        $fields[] = new MTextField('userEmail', 'a@miolo.ufjf.br', 'Email', 50);
        $valids[] = new MEmailValidator('userEmail', '', 'required');

        $fields[] = new MTextField('userCEP', '36100-000', 'CEP', 15);
        $valids[] = new MCepValidator('userCEP', '', 'required');

        $fields[] = new MTextField('userPhone', '333-3333', _M('Phone', $module), 15);
        $valids[] = new MPhoneValidator('userPhone', '', 'required');

        $fields[] = new MTextField('meetingTime', '12:12', _M('Time', $module), 15);
        $valids[] = new MTimeValidator('meetingTime', '', 'required');

        $fields[] = new MTextField('userCPF', '', 'CPF', 20);
        $valids[] = new MCPFValidator('userCPF', '', 'optional');

        $fields[] = new MTextField('employerCNPJ', '', 'CNPJ', 20);
        $valids[] = new MCNPJValidator('employerCNPJ', '', 'optional');

        $fields[] = new MTextField('dateTimeDMY', '09/08/2011 09:15', _M('Date and time', $module), 20);
        $valids[] = new MDateTimeDMYValidator('dateTimeDMY', '', 'required');

        $fields[] = new MTextField('dateDMY', '12/07/2003', _M('Date @1', $module, 'DMY'), 20);
        $valids[] = new MDateDMYValidator('dateDMY', '', 'required');

        $fields[] = new MTextField('dateYMD', '2003/07/31', _M('Date @1', $module, 'YMD'), 20);
        $valids[] = new MDateYMDValidator('dateYMD', '', 'required');

        $fields[] = new MTextField('compareField', '5', _M('Compare', $module), 5, ' >= 10');
        $valids[] = new MCompareValidator('compareField', '', '>=', '10', 'i', 'required');

        $fields[] = new MTextField('integerRange', '5', _M('Integer Range', $module), 5, '[3..10]');
        $valids[] = new MRangeValidator('integerRange', '', '3', '10', 'i', 'required');

        $fields[] = new MTextField('stringRange', 'cor', _M('String Range', $module), 20, '[a..f]');
        $valids[] = new MRangeValidator('stringRange', '', 'a', 'f', 's', 'required');

        $fields[] = new MTextField('dateRange', '12/07/2003', _M('Date Range', $module), 20, '[10/03/2003..05/10/2003]');
        $valids[] = new MRangeValidator('dateRange', '', '10/03/2003', '05/10/2003', 'd', 'required');

        $fields[] = new MPasswordField('passwd', '', _M('Password', $module), 20);
        $valids['passwd'] = new MPasswordValidator('passwd');
        $valids['passwd']->setMinStrength(MPasswordValidator::STRENGTH_VERY_STRONG);

        $fields[] = new MTextField('regexp', '', _M('Regular Expression', $module), 20, 'regexp: [A-Z]{1}[a-z]+');
        $valids[] = new MRegExpValidator('regexp', '', '[A-Z]{1}[a-z]+');

        $fields[] = new MTextField('number', '0', _M('Integer', $module), 20);
        $valids[] = new MIntegerValidator('number', '', 'required');

        $fields[] = new MTextField('floatingNumber', '0', _M('Float', $module), 20);
        $valids[] = new MFloatValidator('floatingNumber');


        $buttons[] = new MBackButton();
        $buttons[] = new MButton('submit_button', _M('Submit', $module));

        $this->setFields($fields);
        $this->setValidators($valids);
        $this->setButtons($buttons);
    }

    public function submit_button_click($args)
    {
        $module = MIOLO::getCurrentModule();

        if ( $this->validate() )
        {
            new MMessageSuccess(_M('Form data is valid', $module));
        }
        else
        {
            new MMessageWarning(_M('Form data is not valid', $module));
        }
    }

    public function getValidationTitleDiv($jsValidation)
    {
        $module = MIOLO::getCurrentModule();
        $title = array();

        $title[] = new MLabel(
            _M('JS Validation is ', $module) . ($jsValidation ? _M('enabled', $module) : _M('disabled', $module)),
            '', 
            true
        );
        $title[] = new MHiddenField('currentJsValidation', $jsValidation ? 'true' : '');
        $title[] = new MButton('btnToggle', _M('Toggle', $module), ':toggleJsValidation');

        return $title;
    }
    
    public function toggleJsValidation()
    {
        $jsValidation = MIOLO::_REQUEST('currentJsValidation');
        $new = $jsValidation ? false : true;
        $this->setJsValidationEnabled($new);

        $this->setResponse($this->getValidationTitleDiv($new), 'divTitle');

        /* 
         * This was added to make the javascript validation work when changing 
         * validation mode dynamically.
         */
        if ( $new )
        {
            $this->generateScript();
        }
    }
}
