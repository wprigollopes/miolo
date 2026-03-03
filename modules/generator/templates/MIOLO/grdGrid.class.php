
/**
 * Grid da tabela %TABLE%.
 *
 * @author %AUTHOR%
 *
 * \b Maintainers: \n
 * %AUTHOR%
 *
 * @since
 * Creation date %CURRENT_DATE%
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) %CURRENT_YEAR% SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 */

class grd%TABLE_CLASS_NAME% extends MGrid
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        %GRID_COLUMNS%

        parent::__construct(NULL, $columns, NULL);

        $args = array(
            'event' => 'acaoEditar:click',
            'function' => 'edit',
        );
        $hrefUpdate = $MIOLO->getActionURL($module, $action, '%0%', $args);
        $args = array(
            'event' => 'acaoRemover:click',
            'function' => 'search',
        );
        $hrefDelete = $MIOLO->getActionURL($module, $action, '%0%', $args);

        $this->addActionUpdate($hrefUpdate);
        $this->addActionDelete($hrefDelete);
        $this->setTitle(_M('%TABLE_CLASS_NAME%', $module));
    }
}
