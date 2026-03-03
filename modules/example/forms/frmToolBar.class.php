<?php

class frmToolBar extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Toolbar', MIOLO::getCurrentModule()));
        $this->eventHandler();

        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        // create a new toolbar
        $fields[0] = new MToolBar('toolBar', '', MToolbar::TYPE_ICON_TEXT);
        $fields[0]->setWidth('100%');

        // define fields for the form
        $fields[] = new MTextField('userName', MForm::getFormValue('userName'), _M('Name', $module));
        $fields[] = new MTextField('userAddress', MForm::getFormValue('userAddress'), _M('Address', $module));
        $fields[] = new MSpacer();

        // set the fields on the form
        $this->setFields($fields);
        // label with
        $this->setLabelWidth('10');
        // set the action of the form's close button
        $this->setClose($MIOLO->getActionURL($module, 'main:controls'));

        $this->setButtons(new MBackButton());
    }

    public function tbBtnNew_click()
    {
        $this->toolBar->disableButton(MToolBar::BUTTON_NEW);
        $this->toolBar->disableButton(MToolBar::BUTTON_DELETE);
        $this->toolBar->disableButton(MToolBar::BUTTON_SEARCH);

        $text = new MText('labelText', _M('New record', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    public function tbBtnSave_click()
    {
        $this->toolBar->disableButton(MToolBar::BUTTON_SAVE);

        $text = new MText('labelText', _M('Saving', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    public function tbBtnDelete_click()
    {
        $this->toolBar->disableButton(MToolBar::BUTTON_DELETE);
        $this->toolBar->disableButton(MToolBar::BUTTON_SAVE);

        $text = new MText('labelText', _M('Deleting', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    public function tbBtnSearch_click()
    {
        $this->toolBar->disableButton(MToolBar::BUTTON_SEARCH);
        $this->toolBar->disableButton(MToolBar::BUTTON_SAVE);
        $this->toolBar->disableButton(MToolBar::BUTTON_DELETE);

        $text = new MText('labelText', _M('Searching, please wait', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    public function tbBtnPrint_click()
    {
        $this->toolBar->disableButton(MToolBar::BUTTON_PRINT);
        $this->toolBar->disableButton(MToolBar::BUTTON_SAVE);
        $this->toolBar->disableButton(MToolBar::BUTTON_DELETE);

        $text = new MText('labelText', _M('Prepare the printer', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }

    public function tbBtnExit_click()
    {
        $text = new MText('labelText', _M('Bye bye, folk', MIOLO::getCurrentModule()));

        // define the text color
        $sender->color = $text->color = 'blue';

        // add the field to the form
        $this->addField($text);
    }
}
?>
