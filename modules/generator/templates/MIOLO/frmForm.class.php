
/**
 * Form to insert, edit and remove records from the %TABLE% table.
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
class frm%TABLE_CLASS_NAME% extends MForm
{
    /**
 * Form constructor.
 */
    public function __construct()
    {
        parent::__construct(_M('%TABLE_CLASS_NAME%', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    /**
 * Create form fields.
 */
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = MMessage::getMessageContainer();

        $fields['toolbar'] = new MToolBar('toolbar');
        $fields['toolbar']->hideButton(array(MToolbar::BUTTON_DELETE, MToolbar::BUTTON_PRINT, MToolBar::BUTTON_RESET, MToolBar::BUTTON_EXIT));

        $readOnly = MIOLO::_REQUEST('function') == 'edit';

        %FORM_FIELDS%

        $this->setFields($fields);

        $buttons[] = new MButton('botaoVoltar', _M('Voltar', $module), ':botaoVoltar_click');
        $buttons[] = new MButton('botaoSalvar', _M('Salvar', $module));
        $this->setButtons($buttons);

        %FORM_VALIDATORS%
        $this->setValidators($validators);
    }

    /**
 * Edit button action.
 */
    public function acaoEditar_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $items = explode(',', MIOLO::_REQUEST('item'));
        $filter = (object) array(
            %FORM_UPDATE_REQUEST%
        );

        $business = $MIOLO->getBusiness($module, '%BUSINESS_NAME%');
        $data = $business->search($filter);
        $line = $data[0];

        %FORM_UPDATE_SETTER%
    }

    /**
 * Save button action.
 */
    public function botaoSalvar_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $business = $MIOLO->getBusiness($module, '%BUSINESS_NAME%', $this->getData());

        switch ( MIOLO::_REQUEST('function') )
        {
            case 'insert':
                try
                {
                    if ( $business->insert() )
                    {
                        new MMessageSuccess(_M('Registro inserido com sucesso!', $module));
                    }
                }
                catch ( Exception $e )
                {
                    new MMessageError(_M('Não foi possível inserir o registro.', $module));
                }
                break;
            case 'edit':
                if ( $business->update() )
                {
                    new MMessageSuccess(_M('Registro atualizado com sucesso!', $module));
                }
                else
                {
                    new MMessageError(_M('Não foi possível editar o registro.', $module));
                }
                break;
        }
    }

    /**
 * Back button action.
 */
    public function botaoVoltar_click()
    {
        $MIOLO = MIOLO::getInstance();
        $url = $MIOLO->getActionURL(MIOLO::getCurrentModule(), MIOLO::getCurrentAction(), '', array('function' => 'search'));
        $MIOLO->page->redirect($url);
    }
}
