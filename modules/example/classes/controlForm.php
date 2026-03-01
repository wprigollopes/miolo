<?php

/**
 * Form to be used in examples.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/12/02
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
class controlForm extends MForm
{
    /**
     * Control form constructor.
     *
     * @param string $title Form title.
     * @param string $file Form file to enable "View source" link. Use __FILE__.
     */
    public function __construct($title, $file=NULL)
    {
        parent::__construct($title);

        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }

        // Creates a link to open the source code
        if ( $file )
        {
            $this->addField(new ViewSource($file));
        }
    }

    /**
     * Create fields.
     */
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $jsClose = "javascript:dojo.byId('controlFormWelcome').parentNode.removeChild(dojo.byId('controlFormWelcome'));";
        $close = new MLinkButton(NULL, _M('Close', $module), $jsClose);
        $close->addStyle('float', 'right');
        $welcome = _M('Hello! This is a control form.', $module);
        $welcome .= $close->generate() . '<br>';
        $welcome .= _M('Here you can save example data and do some other tests using the buttons that are at the bottom of this page. Enjoy!', $module);
        $static = MMessage::getStaticMessage('controlFormWelcome', $welcome, MMessage::TYPE_INFORMATION);
        $static->setWidth(300);
        $static->addStyle('position', 'absolute');
        $static->addStyle('right', '20px');
        $static->addStyle('line-height', '18px');
        $fields[] = $static;

        $fields[] = MMessage::getMessageContainer();
        $fields[] = MPopup::getPopupContainer();
        $this->addFields($fields);

        $buttons[] = $back = new MBackButton();
        $buttons[] = $show = new MButton('btnShowSetData', _M('Display data', $module), ':showData');
        $buttons[] = $list = new MButton('btnList', _M('List', $module), ':listData');
        $buttons[] = $valid = new MButton('btnValidate', _M('Validate', $module), ':validate');
        $buttons[] = $save = new MButton('btnSubmit', _M('Save', $module), ':save');

        $show->setJsHint(_M('Show me the @1 result', $module, 'setData'));
        $list->setJsHint(_M('Show me how saved data is stored at the database', $module));

        $back->addStyle('min-width', '60px');
        $show->addStyle('min-width', '60px');
        $list->addStyle('min-width', '60px');
        $valid->addStyle('min-width', '60px');
        $save->addStyle('min-width', '60px');

        $this->setButtons($buttons);
    }

    /**
     * Show getData result.
     */
    public function showData()
    {
        $module = MIOLO::getCurrentModule();
        $print = '<pre>' . print_r($this->getData(), 1) . '</pre>';
        $content[] = new MDiv(NULL, $print);
        MPopup::show('showData', $content, _M('Form data', $module));
    }

    /**
     * Show data that are stored on the database.
     */
    public function listData()
    {
        $module = MIOLO::getCurrentModule();

        $control = $this->manager->getBusiness($module, 'control');

        $filter = new stdClass();
        $filter->form = get_class($this);
        $data = $control->search('field, value', $filter);

        $columns[] = new MGridColumn(_M('Field', $module));
        $columns[] = new MGridColumn(_M('Value', $module));
        $fields[] = new MGrid($data, $columns, NULL);

        $content[] = new MDiv(NULL, $fields);

        MPopup::show('listData', $content, _M('Database data', $module));
    }

    /**
     * Save data on the database.
     */
    public function save()
    {
        $module = MIOLO::getCurrentModule();
        $data = $this->getData();
        $ok = false;
        $thereIsData = false;

        foreach ( $data as $field => $value )
        {
            if ( $value )
            {
                $thereIsData = true;

                // Save field value
                $control = $this->manager->getBusiness($module, 'control');
                $control->setForm(get_class($this));
                $control->setField($field);
                $control->setValue($value);

                // If can't insert, probably is already inserted
                try
                {
                    $ok = $control->insert() ? true : false;
                }
                // So try to update it
                catch ( Exception $e )
                {
                    $ok = $control->update() ? true : false;
                }
            }
        }

        if ( !$thereIsData )
        {
            new MMessageInformation(_M('There is no data to be saved', $module));
        }
        elseif ( $ok )
        {
            new MMessageSuccess(_M('Saved successfully', $module));
        }
        else
        {
            new MMessageWarning(_M('Data was not saved', $module));
        }
    }

    /**
     * Validate user values.
     */
    public function validate()
    {
        $module = MIOLO::getCurrentModule();

        if ( count($this->validations) == 0 )
        {
            new MMessageInformation(_M('This form does not have validators', $module));
        }
        else
        {
            if ( parent::validate() )
            {
                new MMessageSuccess(_M('Form data is valid', $module));
            }
            else
            {
                new MMessageWarning(_M('Form data is not valid', $module));
            }
        }
    }
}
