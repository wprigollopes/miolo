<?php

/**
 * A class for forms, extending from MBaseForm.
 * This class acts as a Decorator for MBaseForm, adding a outer box for the form.
 *
 * @author Vilson Cristiano Gärtner [vilson@solis.coop.br]
 * @author Ely Edison Matos [ely.matos@ufjf.edu.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2006/03/08
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2006-2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 */

$MIOLO->uses('model/mcustomfield.class.php');
$MIOLO->uses('model/mcustomvalue.class.php');

class MForm extends MBaseForm
{
    public $formBox;
    public $title;
    public $help;
    public $footer;
    public $bgColor;
    public $align = NULL;

    /**
     * @var array The customs fields array.
     */
    public $mioloCustomFields;

    /**
     * @var mixed The customized id. Only set when it's an edit action on a form for custom fields.
     */
    public $mioloCustomizedId;

    /**
     * This is the constructor of the Form class. It takes a title and an
     * action url as optional parameters. The action URL is typically 
     * obtained by calling the <code>MIOLO->getActionURL()</code> method.
     *
     * @param $title  (string) the form's title string
     * @param $action (string) the URL for the forms <code>ACTION</code>
     *                attribute.
     */
    public function __construct($title='',$action='',$close='',$icon='')
    {
        $this->formBox = new MBox($title,$close,$icon);
        $this->title  = $title;
        parent::__construct($action);
    }

    
    /**
     * Obtains the content of this form's title. Observe, that this
     * can be anything other as a simple text string too, such as array of
     * strings and an object implementing the <code>Generate()</code> method.
     *
     * @return (Mixed &) a reference to the title of the form
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    
    /**
     * Set the form's title
     *
     * @param (string) $title Form title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->caption = $title;
        $this->formBox->setCaption($title);
    }

    /**
     * Sets the form's close action
     *
     * @param (string) $action Form action
     */
    public function setClose($action)
    {
        if ($this->formBox->boxTitle != NULL)
        {
           $this->formBox->boxTitle->setClose($action);
        }
    }

    /**
     * Sets the form's icon
     *
     * @param (string) $icon Icon URL
     */
    public function setIcon($icon)
    {
        if ($this->formBox->boxTitle != NULL)
        {
            $this->formBox->boxTitle->setIcon($icon);
        }
    }

    /**
     * Obtains the content title of this form's footer. Observe, that this
     * can be anything other as a simple text string too, such as array of
     * strings and an object implementing the <code>Generate()</code> method.
     *
     * @return (Mixed &) a reference to the footer of the form
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Form's footer.
     * Sets the form's footer content.
     *
     * @param $footer (tipo) Footer content
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * Sets an URL to a help document. This document will be opened via
     * JavaScript in a new window. If this URL is set, the form will display
     * a help button in it's title bar.
     *
     * @param (string) $href URL
     */
    public function setHelp($href)
    {
        $this->help = $href;
    }
    
    
    /**
     * Obtains form fields in a FormData object.
     *
     * @return (Object) Form fields
     */
    public function getData()
    {
        return $this->collectInput( new FormData() );
    }

    /**
     * Obtains form fields in a FormData object, including dynamically added fields.
     *
     * @return (Object) Form fields
     */
    public function getAjaxData()
    {
        return (object) $_REQUEST;
    }

    /**
     * Set data on the form fields.
     * Set form fields values. A subclassed form will likely override this
     * method, in order to provide a specialized processing for the passed
     * data object. <br><br>
     * This method simply calls the <code>_setData()</code> method of the
     * <code>Form</code> class.
     * @example
     * $data = new FormData();
     * $form->setData( $data );
     *
     * @param object $data Object containing the field values
     */
    public function setData($data)
    {
        $this->_setData($data);
    }
    
    
    /**
     * This method sets the form field values in way, that it iterates
     * thru the list of fields and sets the values of all fields matching
     * the data object attribute names.
     * 
     * In short it does the following<br>
     * @example 
     * <code>$frm_name = $data->name;</code>
     * 
     * This implies that the form field names must be identical the data
     * member names.
     * 
	 * @param $data (array) Data to be assigned to formfields
 	 *
 	 * @return (void)
 	 */
    private function _setData($data)
    {
        foreach ($this->fields as $field => $name)
        {
            $name = $this->fields[$field]->name;
            if ( $name )
            {
                if ( ($this->fields[$field] instanceof MRadioButton) || 
                     ($this->fields[$field] instanceof MCheckBox) )
                {
                    $dataValue = isset($data->$name) ? $data->$name : null;
                    $this->fields[$field]->checked = ( $dataValue == $this->fields[$field]->value );
                }
                else
                {
                    $bracketPos = strpos($name, '[');

                    // Check if input is an array
                    if ( $bracketPos !== false )
                    {
                        $subName = substr($name, 0, $bracketPos);
                        $array = isset($data->$subName) ? $data->$subName : null;

                        $index = '';
                        $i = $bracketPos + 1;
                        $char = substr($name, $i++, 1);

                        // Find index
                        while ( $char != ']' )
                        {
                            $index .= $char;
                            $char = substr($name, $i++, 1);
                        }

                        if ( $index != '' )
                        {
                            $intIndex = (int) $index;
                            $value = isset($array[$index]) ? $array[$index] : null;
                        }
                    }
                    else
                    {
                        $value = isset($data->$name) ? $data->$name : null;
                    }

                    if ( ($this->fields[$field] instanceof MFormControl) &&
                         (isset($value)) )
                    {
                        $this->fields[$field]->setValue($value);
                        $_POST[$name] = $value;
                    }
                }
            }
        }
    }
    
    
    /**
     * Obtains a form field's value
     */
    public function getFieldValue($name,$value=false)
    {   
        $field = $this->fields[$name];
        return ($field ? $field->getValue() : NULL);
    }
    
    
    /**
     * Set a form field's value
     */
    public function setFieldValue($name,$value)
    {   
        $field = $this->fields[$name];
        $field->setValue($value);
    }
    
    
    /**
     * Set a form field's validator
     */
    public function setFieldValidator($name,$value)
    {   
        for ( $i=0, $n = count($field); $i < $n; $i++ )
        {
            if ( $name == $this->fields[$i]->name )
            {
                $this->fields[$i]->validator = $value;
                break;
            }
        }
    }
    
    
    /**
     * Get a reference for a form field
     */
    public function & GetField($name)
    {
        return $this->fields[$name];
    }
    
    
    /**
     * Get a reference for a button
     */
    public function & GetButton($name)
    {
        for ( $i=0, $n = count($this->buttons); $i < $n; $i++ )
        {
            if ( $name == $this->buttons[$i]->name )
            {
                return $this->buttons[$i];
            }
        }
    }
    
    
    /**
     * Get a reference for page
     */
    public function & GetPage()
    {
        return $this->page;
    }
    
    
    /**
     * Set reference for page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
    
    
    /**
     * Set a form field's attribute a value
     */
    public function setFieldAttr($name,$attr,$value)
    {   
//      $field = $this->$name;
//      $field->$attr = $value;
        $this->fields[$name]->$attr = $value; 
    }
    
    
    /**
     * Get a form field's attribute value
     */
    public function getFieldAttr($name,$attr, $index=NULL)
    {   
        $field = $this->fields[$name];
        if ( is_array($field->$attr) )
        {
            $a = $field->$attr;
            $value = $a[$index];
        }
        else
        {
          $value = $field->$attr;
        }
        return $value;
    }
    
    
    /**
     * Set a form field's attribute a value
     */
    public function setButtonAttr($name,$attr,$value)
    {   
        $button = &$this->getButton($name);
        $button->$attr = $value;
    }
    
    
    /**
     * Set CSS for the field.
     *
     * @param (string) $name
     * @param (integer) $top
     * @param (integer) $left
     * @param (integer) $width
     * @param (string) $position
     */
    public function setFieldCSS($name,$top,$left,$width=NULL, $position='absolute')
    {   
        $field = $this->$name;
//        $top = $this->formBox->top + $top;
//        $left = $this->formBox->left + $left;
        if ($width) 
        {
            $field->width = "{$width}px";
        }
        
        $field->top = "{$top}px";
        $field->left = "{$left}px";
        
        if ($position) 
        {
            $field->position = $position;
        }
        
        $field->formMode = 2;
    }

    /**
     * Set form CSS
     *
     * @param (integer) $height
     * @param (integer) $width
     * @param (integer) $top
     * @param (integer) $left
     * @param (integer) $buttons
     * @param (string) $position
     */
    public function setFormCSS($height=0, $width=0, $top=0, $left=0, $buttons=0, $position='absolute')
    {
        if ($height) 
        {
            $this->formBox->addStyle('height',"{$height}px");
        }
        
        if ($width) 
        {
            $this->formBox->addStyle('width',"{$width}px");
        }
        
        if ($top) 
        {
            $this->formBox->addStyle('top',"{$top}px");
        }
        
        if ($left) 
        {
            $this->formBox->addStyle('left',"{$left}px");
        }
        
        if ($position) 
        {
            $this->formBox->addStyle('position',$position);
        }
        
        $this->cssButtons = "{$buttons}px";
        $this->cssForm = true;
    }
    
    public function setBackgroundColor($bgcolor)
    {
        $this->bgColor = $bgcolor; 
    }

    public function setAlign($value)
    {
        $this->align = $value;
    }
    
    public function setWidth($width=NULL)
    { 
        if ($width) $this->formBox->addStyle('width',"{$width}");
    }
    
    public function setHeight($height=NULL)
    { 
        if ($height) $this->formBox->addStyle('height',"{$height}");
    }

/**
 * Brief Description.
 * Complete Description.
 *
 * @returns (tipo) desc
 *
 */
    public function generateFooter()
    {
    }
   
    
    public function generateInner()
    {
        parent::generateInner();
        $body = $this->inner;
        $footer = $this->generateFooter();
        $this->formBox->setControls(array($body, $footer));
        $this->formBox->setClass("mFormOuter");
        $this->setClass("mForm");
        if (!is_null($this->align))
        {
            $this->addStyle('text-align',$this->align);
        }
        $this->inner = $this->formBox;
    }

    /**
     * Add the custom fields of the given identifier.
     *
     * @param string $identifier Identifier.
     * @param string $customizedId Primary key of the main table (for editing form).
     * @param string $suffix Suffix to be added to each field name.
     * @return array The custom fields.
     */
    public function addCustomFields($identifier, $customizedId=NULL, $suffix='')
    {
        $fields = $this->getCustomFields($identifier, $customizedId, $suffix);

        if ( count($fields) > 0 )
        {
            $this->addFields($fields);
        }
    }

    /**
     * @return array
     */
    public function getCustomFields($identifier, $customizedId=NULL, $suffix='')
    {
        if ( !$identifier )
        {
            return;
        }

        $this->mioloCustomizedId = $customizedId;

        // If customized id is set, then it's an edit action
        if ( isset($customizedId) )
        {
            // Load the custom fields values
            $data = MCustomValue26::getFieldValues($identifier, $customizedId);
        }

        $this->mioloCustomFields = MCustomField26::listByIdentifier($identifier);

        $fieldsNormalizados = $this->normalizaCamposCustomizados($this->mioloCustomFields);

        $fields = $this->generateCustomFields($fieldsNormalizados, $data, $suffix);

        return $fields;
    }

    /**
     * Função criada para ser sobrescrita em classes filhas.
     *
     * @param array $customFields
     * @return array
     */
    protected function normalizaCamposCustomizados($customFields)
    {
        return $customFields;
    }

    /**
     * @return array
     */
    public function generateCustomFields($mioloCustomFields = array(), $data = null, $suffix = null)
    {
        $fields = array();

        // Create a copy of the array to ignore case problems
        $dataCopy = array_change_key_case((array) $data, CASE_LOWER);

        $listNoYes = array(
            'f' => _M('No'),
            't' => _M('Yes'),
        );

        foreach ( $mioloCustomFields as $cfield )
        {
            $cfield instanceof MCustomField26;

            $field = NULL;
            $validator = NULL;

            $cfield->suffix = $suffix;
            $id = $cfield->getInputId();
            $value = isset($dataCopy) ? $dataCopy[strtolower($id)] : $cfield->defaultValue;
            $label = _M($cfield->label, MIOLO::getCurrentModule());

            if ( $cfield->isRequired() )
            {
                $validator = new MRequiredValidator($id, $label);
            }

            switch ( $cfield->fieldFormat )
            {
                case MCustomField26::FORMAT_BOOLEAN:
                    $field = new MSelection($id, $value, $label, $listNoYes);
                    break;

                case MCustomField26::FORMAT_DATE:
                    $field = new MCalendarField($id, $value, $label);

                    if ( $cfield->isRequired() && $cfield->isEditable() && $cfield->isVisible() )
                    {
                        $field->validator->type = 'required';
                        $validator = NULL;
                    }

                    break;

                case MCustomField26::FORMAT_DECIMAL:
                    $field = new MFloatField($id, $value, $label);
                    break;

                case MCustomField26::FORMAT_INTEGER:
                    $field = new MTextField($id, $value, $label);
                    $validator = new MIntegerValidator($id, $label, $cfield->isRequired() ? 'required' : 'optional');
                    break;

                case MCustomField26::FORMAT_LIST:
                    $field = new MSelection($id, $value, $label, $cfield->getListValues());
                    break;

                case MCustomField26::FORMAT_LISTSQL:
                    $field = new MSelection($id, $value, $label, $cfield->getListSQL());
                    $field->hint = $cfield->getFieldHint();
                    break;

                case MCustomField26::FORMAT_LONG_TEXT:
                    $field = new MMultilineField($id, $value, $label, 25, 5, 20);
                    break;

                case MCustomField26::FORMAT_TEXT:
                    $field = new MTextField($id, $value, $label);
                    break;
            }

            if ( $cfield->maxLength != 0 )
            {
                if ( !$validator )
                {
                    $validator = new MRegExpValidator($id, $label);
                }

                $validator->min = $cfield->minLength;
                $validator->max = $cfield->maxLength;
            }

            if ( $field )
            {
                if ( !$cfield->isEditable() )
                {
                    $field->setReadOnly(true);
                    $validator = NULL;
                }

                if ( !$cfield->isVisible() )
                {
                    $field->addBoxStyle('display', 'none');
                    $validator = NULL;
                }

                $fields[] = $field;
            }

            if ( $validator != NULL )
            {
                $this->addValidator($validator);
            }
        }

        return $fields;
    }

    /**
     * Save the custom field values.
     *
     * @param mixed $customizedId The primary key of the related table.
     * @return boolean Whether was successfully saved.
     */
    public function saveCustomFields($customizedId, $data = null, $identifier = null)
    {
        if ( strlen($identifier) > 0 )
        {
            $this->getCustomFields($identifier, $customizedId);
        }

        if ( count($this->mioloCustomFields) == 0 )
        {
            return NULL;
        }

        // Se nao passar dados, pega o padrao getData()
        if ( !$data )
        {
            $data = $this->getData();
        }

        $ok = false;

        foreach ( $this->mioloCustomFields as $cf )
        {
            $cf instanceof MCustomField26;

            $inputId = $cf->getInputId();

            $customValue = new MCustomValue26();
            $customValue->customizedId = $customizedId;
            $customValue->customFieldId = $cf->id;
            $customValue->value = $data->$inputId;

            // If customized id is set, then it's an edit action
            if ( isset($this->mioloCustomizedId) )
            {
                if ( $customValue->updateByData() )
                {
                    $ok = true;
                }
                else
                {
                    $ok = false;
                    break;
                }
            }
            else
            {
                if ( $customValue->insert() )
                {
                    $ok = true;
                }
                else
                {
                    $ok = false;
                    break;
                }
            }
        }

        return $ok;
    }

    /**
     * Search the custom field values through the identifier and the form data.
     *
     * @param string $identifier Custom field identifier.
     * @return array Values saved at the database.
     */
    public function searchCustomFieldValues($identifier)
    {
        if ( !count($this->mioloCustomFields) )
        {
            return NULL;
        }

        $searchData = array();
        $values = NULL;

        foreach ( $this->mioloCustomFields as $cf )
        {
            $value = MIOLO::_REQUEST($cf->getInputId());

            if ( strlen($value) )
            {
                $searchData[$cf->id] = $value;
            }
        }

        // Comentado para retornar todos dados de campos customizados na grid
//        if ( count($searchData) )
        {
            $values = MCustomValue26::searchFieldValues($identifier, $searchData);
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getCustomFieldValues()
    {
        $values = array();

        foreach ( $this->mioloCustomFields as $cf )
        {
            $cf instanceof MCustomField26;
            $value = MIOLO::_REQUEST($cf->getInputId());

            if ( strlen($value) )
            {
                $values[$cf->getInputId()] = $value;
            }
        }

        return $values;
    }

    /**
     * Delete custom field values.
     *
     * @param mixed The primary key of the related table.
     * @return boolean Whether was successfully deleted.
     */
    public function deleteCustomFieldValues($customizedId)
    {
        if ( count($this->mioloCustomFields) == 0 )
        {
            return NULL;
        }

        $ok = false;

        foreach ( $this->mioloCustomFields as $cf )
        {
            $customValue = new MCustomValue26();
            $customValue->customizedId = $customizedId;
            $customValue->customFieldId = $cf->id;

            $ok = $customValue->deleteByCustomizedId();
        }

        return $ok;
    }
}

?>