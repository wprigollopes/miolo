/**
 * Form class
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * @version $id$
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 * Jonas Guilherme Dahmer [jonas@solis.coop.br]
 *
 * @since
 * Creation date %CURRENT_DATE%
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Solucoes Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 Solis - Cooperativa de Solucoes Livres \n
 *
 */
 
class frm%FORM_NAME% extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('%FORM_LABEL%', MIOLO::getCurrentModule()));
    }

    public function createFields()
    {
        %FORM_FIELDS%
        $this->setFields($fields);
    }
}
