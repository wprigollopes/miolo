
/**
 * Search form for the %TABLE% table.
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
class frmBusca%TABLE_CLASS_NAME% extends MForm
{
    /**
 * @var object Objeto MGrid
 */
    public $grid;

    /**
 * Form constructor.
 */
    public function __construct()
    {
        parent::__construct(_M('Busca de %TABLE_CLASS_NAME%', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    /**
 * Create the form fields.
 */
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = MMessage::getMessageContainer();

        $fields['toolbar'] = new MToolBar('toolbar');
        $fields['toolbar']->disableButton(MToolbar::BUTTON_SEARCH);
        $fields['toolbar']->hideButton(array(MToolbar::BUTTON_DELETE, MToolbar::BUTTON_PRINT, MToolBar::BUTTON_RESET));

        %FORM_FILTERS%

        $searchButtons[] = new MButton('botaoLimpar', _M('Limpar', $module));
        $searchButtons[] = new MButton('botaoPesquisar', _M('Pesquisar', $module));
        $fields[] = new MDiv(NULL, $searchButtons, NULL, 'align=center');

        $MIOLO = MIOLO::getInstance();
        $business = $MIOLO->getBusiness($module, '%BUSINESS_NAME%');
        $this->grid = $this->manager->getUI()->getGrid($module, 'grd%TABLE_CLASS_NAME%');
        $this->grid->setData($business->search());
        
        $fields[] = new MDiv('divGrid', $this->grid->generate());
        $this->setFields($fields);

        $this->setButtons(array());
    }

    /**
 * Save button action.
 */
    public function botaoPesquisar_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $data = $this->getData();

        $business = $MIOLO->getBusiness($module, '%BUSINESS_NAME%');
        $this->grid->setData($business->search($data));
        $this->setResponse($this->grid->generate(), 'divGrid');
    }

    /**
 * Clear button action.
 */
    public function botaoLimpar_click()
    {
        %FORM_CLEAR_FIELDS%
    }

    /**
 * Delete button click
 *
 * @param array $args Request arguments
 */
    public function acaoRemover_click($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $business = $MIOLO->getBusiness($module, '%BUSINESS_NAME%');

        $items = explode(',', MIOLO::_REQUEST('item'));
        %FORM_DELETE_SETTER%

        if ( $business->delete() )
        {
            $this->grid->setData($business->search($data));
            $this->setResponse($this->grid->generate(), 'divGrid');
            new MMessageSuccess(_M('Registro removido com sucesso!', $module));
        }
    }
}
