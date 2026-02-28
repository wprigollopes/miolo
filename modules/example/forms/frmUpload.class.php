<?php

class frmUpload extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Upload', $module));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = new MFileField('singleFile', '', _M('Single file', $module));

        $fields[] = $multipleFiles = new MFileField('multipleFiles', '', _M('Multiple files', $module));
        $multipleFiles->setIsMultiple(true);

        $this->setFields($fields);
    }

    public function submit_button_click($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $path = $MIOLO->getConf('home.html') . "/files/";
        $uploadedFiles = MFileField::uploadFiles($path);

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
        else
        {
            new MMessageWarning(_M('No files uploaded', $module));
        }
    }
}

?>
