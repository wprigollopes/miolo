<?php

/**
 * Template upload form
 *
 * TODO: Remove and edit templates
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
 * Creation date 2011/02/15
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

$MIOLO->uses('forms/frmGenerate.php', $module);

class frmTemplate extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Template', $module));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
        $this->defaultButton = NULL;
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = MPopup::getPopupContainer();

        $template[] = $this->getTemplateList();
        $fields[] = new MFormContainer('cTemplate', $template, 'vertical', MFormControl::FORM_MODE_SHOW_SIDE);

        $fields[] = new MFileField('templateFile', '', _M('Template upload', $module));
        
        $buttons[] = new MButton('editButton', _M('Edit'));
        $buttons[] = new MButton('uploadButton', _M('Upload'));
        $buttons[] = new MButton('downloadButton', _M('Download'));
        $buttons[] = new MButton('removeButton', _M('Remove', $module));
        $fields[] = new MDiv(NULL, $buttons, NULL, 'align="center"');

        $fields[] = $div = new MDiv('fileResponse', NULL);
        $div->addStyle('display', 'none');

        $this->setFields($fields);
    }

    public function getTemplateList()
    {
        $module = MIOLO::getCurrentModule();

        $templates = frmGenerate::getTemplates();
        
        $dirs = frmGenerate::getTemplatesDirectories();

        foreach ( $dirs as $dir )
        {
            $templatesFromDir = frmGenerate::getTemplatesFromDirectory($dir);
            if ( count($templatesFromDir) > 0 )
            {
                foreach ( $templatesFromDir as $t )
                {
                    $templates["$dir/$t"] = "$dir/$t";
                }
            }
        }
        
        $template = new MMultiSelection('template', NULL, _M('Template list', $module), $templates);
        $template->addStyle('width', '215px');

        return $template;
    }

    public function saveButton_click($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $file = current($args->template);
        $code = $args->code;
        $path = $MIOLO->getConf('home.modules') . "/$module/templates";

        $handler = fopen("$path/$file", 'w');
        $write = fwrite($handler, $code);
        fclose($handler);

        MPopup::remove();
        if ( $write === false )
        {
            new MMessageWarning(_M('Template not saved', $module));
        }
        else
        {
            new MMessageSuccess(_M('Template saved', $module));
        }
    }

    public function editButton_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        
        $data = $this->getData();
        
        if ( count($data->template) == 0 )
        {
            new MMessageWarning(_M('Please select a template to edit', $module));
            return;
        }
        elseif ( count($data->template) > 1 )
        {
            new MMessageWarning(_M('You cannot edit more than one template at once', $module));
            return;
        }
        
        $template = current($data->template);

        $path = $MIOLO->getConf('home.modules') . "/$module/templates";
        $code = fread(fopen("$path/$template", 'r'), filesize("$path/$template"));

        $content[] = new MMultilineField('code', $code, NULL, 20, 25, 100);

        $buttons[] = new MButton('cancelButton', _M('Cancel'), 'javascript:mpopup.remove();');
        $buttons[] = new MButton('saveButton', _M('Save'));
        $content[] = new MDiv(NULL, $buttons, NULL, 'align="center"');
        
        MPopup::show(NULL, $content, _M('Edit template @1', $module, $template));
    }

    public function confirmOverwrite()
    {
        $module = MIOLO::getCurrentModule();
        $args = MUtil::getAjaxActionArgs();

        if ( !copy($args->tmpFile, $args->path) )
        {
            new MMessageWarning(_M('The file could not be uploaded', $module));
        }

        unlink($args->tmpFile);

        $control = $this->getTemplateList();
        MPopup::remove();
        $this->setResponse($control, 'cTemplate', true);

        new MMessageSuccess(_M('File @1 uploaded successfully', $module, $args->name));
    }

    public function uploadFiles($path)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $uploadInfo = MFileField::getUploadInfo();

        if ( !is_array($uploadInfo) )
        {
            return;
        }

        $uploadedFiles = array();

        foreach ( $uploadInfo as $fileInfo )
        {
            list($fileName, $tmpFile) = explode(';', $fileInfo);
            $tmpFile = $MIOLO->getConf('home.html') . "/files/tmp/$tmpFile";

            if ( file_exists("$path/$fileName") )
            {
                $message = _M('The template already exists, do you want to overwrite it?', $module);
                $args = array(
                    'tmpFile' => $tmpFile,
                    'name' => $fileName,
                    'path' => "$path/$fileName"
                );
                $actionYes = MUtil::getAjaxAction('confirmOverwrite', $args);

                MPopup::confirm($message, _M('Confirm overwrite', $module), $actionYes, 'mpopup.remove();');
                return;
            }

            if ( copy($tmpFile, "$path/$fileName") )
            {
                unlink($tmpFile);
                $uploadedFiles[] = array(
                    'name' => $fileName,
                    'path' => "$path/$fileName"
                );
            }
        }

        return $uploadedFiles;
    }

    public function uploadButton_click($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $path = $MIOLO->getConf('home.modules') . "/$module/templates";
        $uploadedFiles = $this->uploadFiles($path);

        if ( is_array($uploadedFiles) && count($uploadedFiles) == 1 )
        {
            new MMessageSuccess(_M('File @1 uploaded successfully', $module, $uploadedFiles[0]['name']));
        }
        elseif ( count($uploadedFiles) > 1 )
        {
            $files = array();
            foreach( $uploadedFiles as $uploadedFile )
            {
                $files[] = $uploadedFile['name'];
            }
            $files = implode(', ', $files);
            new MMessageSuccess(_M('Files @1 uploaded successfully', $module, $files));
        }
        elseif ( is_array($uploadedFiles) )
        {
            new MMessageWarning(_M('No files uploaded', $module));
        }

        $control = $this->getTemplateList();
        $this->setResponse($control, 'cTemplate', true);
    }

    public function downloadButton_click($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $data = $this->getData();
        $iframe = '';

        foreach ( $data->template as $fileName )
        {
            $path = $MIOLO->getConf('home.modules') . "/$module/templates";
            $file = rand() . end(explode('/', $fileName));
            copy("$path/$fileName", "/tmp/MIOLO_$file");

            if ( file_exists("/tmp/MIOLO_$file") )
            {
                $url = "http://" . $_SERVER['HTTP_HOST'] . "/files/download.php?file=$file";
                $iframe .= "<iframe src='$url'></iframe>";
            }
            else
            {
                $msg = _M('Template file not found!', $module);
                new MMessageWarning($msg);
                return;
            }
        }

        $this->setResponse($iframe, 'fileResponse');
    }

    public function confirmRemove()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $data = $this->getData();
        $path = $MIOLO->getConf('home.modules') . "/$module/templates";

        foreach ( $data->template as $fileName )
        {
            unlink("$path/$fileName");
        }

        $control = $this->getTemplateList();
        MPopup::remove();
        $this->setResponse($control, 'cTemplate', true);

        if ( count($data->template) == 1 )
        {
            new MMessageSuccess(_M('File @1 removed successfully', $module, $fileName));
        }
        else
        {
            new MMessageSuccess(_M('Files removed successfully', $module));
        }
    }

    public function removeButton_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $data = $this->getData();
        
        if ( count($data->template) == 0 )
        {
            new MMessageWarning(_M('Please select the template you want to remove', $module));
            return;
        }
        
        $message = _M('Are you sure you want to remove the template(s)?', $module);
        $actionYes = MUtil::getAjaxAction('confirmRemove');

        MPopup::confirm($message, _M('Confirm remove', $module), $actionYes, 'mpopup.remove();');
    }
}
