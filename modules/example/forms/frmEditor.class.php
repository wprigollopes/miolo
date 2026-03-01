<?php

/**
 * MEditor example
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * @version $id$
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/02/18
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b CopyRight: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 * \b History: \n
 * See history in CVS repository: http://www.miolo.org.br
 *
 */

class frmEditor extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Editor', MIOLO::getCurrentModule()));

        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = MMessage::getMessageContainer();

        $editor = new MEditor('editor', NULL, _M('Editor', $module));
        $editor->addCustomButton(_M('Custom Button'), "miolo.doAjax('insertContent','','{$this->formId}');", '/scripts/ckeditor/plugins/custombutton/custombutton.png');
        // On the JS command, editor is the variable representing the ckeditor instance
        $editor->addCustomButton(_M('Custom Button') . ' 2', "editor.insertHtml('You clicked the second custom button.');", '/scripts/ckeditor/plugins/custombutton/custombutton.png');
        $fields[] = $editor;

        $fields[] = new MDiv('responseDiv', NULL);
        $fields[] = new MButton('btnGenerateEditor', _M('Generate'), ':generateEditor');
        $fields[] = new MButton('btnRemoveEditor', _M('Remove'), "meditor.remove('ajaxEditor');");

        $this->setFields($fields);
    }

    public function insertContent()
    {
        new MMessage('You clicked the first custom button');
        $this->page->onload("CKEDITOR.instances.editor.insertHtml('You clicked the first custom button.');");
    }

    public function generateEditor()
    {
        $module = MIOLO::getCurrentModule();
        $editor = new MEditor('ajaxEditor', NULL, _M('Editor created via AJAX', $module));
        $this->setResponse($editor, 'responseDiv');
    }

    public function submit_button_click($args)
    {
        $module = MIOLO::getCurrentModule();
        $editor = new MEditor('ajaxEditor', $args->ajaxEditor, _M('Editor created via AJAX', $module));
        $this->setResponse($editor, 'responseDiv');

        new MMessage($args->ajaxEditor);
    }
}
