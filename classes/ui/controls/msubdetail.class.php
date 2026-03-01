<?php

/**
 * MSubDetail class
 *
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2009/01/29
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 * 
 */

$MIOLO = MIOLO::getInstance();
$MIOLO->page->addScript('m_subdetail.js');

class MSubDetail extends MBaseGroup
{
    private $item = 'item';
    private $tableItem = '';
    private $actions = '';
    public $title = ''; // table Title
    private $table = NULL;
    private $fields = NULL;
    private $showButtons = true;
    private $overflowWidth = NULL;
    private $overflowHeight = NULL;
    private $overflowType = NULL;
    public $cleanFields = true;
    public $cleanHiddenF = false;
    public $actionCelWidth = false;
    public $transaction = NULL;
    protected $updateButton = false;
    private $namesArray;
    private $classesArray;
    private $validator;
    private static $doLookupAutocomplete = false;
    const BIGIMGWIDTH = 35;
    const IMGWIDTH = 20;
    const STATUS_ADD = 'add';
    const STATUS_EDIT = 'edit';
    const STATUS_REMOVE = 'remove';
    
    private $currentFields = array();
    private $currentColumns = array();
    private $currentValidators = array();
    

    /**
     * Default constructor, need to pass Session Item.
     */
    public function __construct($name, $title, $columns = NULL, $fields = NULL, $opts = true, $align = 'vertical', $border = 'css', $formMode = MFormControl::FORM_MODE_SHOW_SIDE)
    {
        parent::__construct($name, $title, null, $align, $border, $formMode);
        $module = MIOLO::getCurrentModule();
        $MIOLO = MIOLO::getInstance();
        $this->title = $title;
        $this->item = $name;

        // add default column
        $this->addColumn(_M('Actions'), 'left', true, '10%', true, '');
        $this->setColumns($columns);
        $this->setFields($fields);

        // clear the actions for this subdetail
        self::setSessionValue('actions', null, $this->item);

        // add default actions
        if ( $opts )
        {
            $ui = $MIOLO->getUI();

            if ( $MIOLO->checkMobile() )
            {
                $editImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_edit_big.png');
                $rmImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_drop_big.png');
            }
            else
            {
                $editImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_edit.png');
                $rmImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_drop.png');
            }

            $upImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_up.png');
            $downImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_down.png');
            $dupliImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'button_duplicate.png');

            if ( $opts === true )
            {
                $this->addAction('editFromTable', $editImg);
                $this->addAction('removeFromTable', $rmImg);
            }
            else
            if ( is_array($opts) )
            {
                if ( in_array('edit', $opts) )
                {
                    $this->addAction('editFromTable', $editImg);
                }

                if ( in_array('duplicate', $opts) )
                {
                    $this->addAction('duplicate', $dupliImg);
                }

                if ( in_array('remove', $opts) )
                {
                    $this->addAction('removeFromTable', $rmImg);
                }

                if ( in_array('up', $opts) )
                {
                    $this->addAction('upFromTable', $upImg);
                }

                if ( in_array('down', $opts) )
                {
                    $this->addAction('downFromTable', $downImg);
                }

                if ( in_array('noButtons', $opts) )
                {
                    $this->showButtons = false;
                }

                if ( in_array('updateButton', $opts) )
                {
                    $this->setUpdateButton(true);
                }

                if ( in_array('noOrder', $opts) )
                {
                    $this->setOrder(false);
                }
            }
        }

        $this->addStyle('clear', 'both'); // to be able to set the width without problems

        $this->setOrder(true);
        $MIOLO = MIOLO::getInstance();
        // automatically create the arrayItemTemp field for this subdetail, on demand
        $MIOLO->page->onload("msubdetail.addJsField('arrayItemTemp{$name}')");
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Defines a default value to be shown in a specific field each time the fields are cleared
     *
     * This value is called on clear, after add, and after edit.
     *
     * @param String $fieldId the id of the field to receive the value
     * @param String $value the default value for the field
     */
    public function setDefaultValue($fieldId, $value)
    {
        $MIOLO = MIOLO::getInstance();
        $id = $this->item . '_' . $fieldId . '_defaultValue';
        $MIOLO->page->onload("msubdetail.addJsField('$id','$value');");
    }

    /**
     * Defines whether to show the update button or not
     *
     * @param boolean $show
     */
    public function setUpdateButton($show)
    {
        $this->updateButton = $show;
        self::setSessionValue('updateButton', $show, $this->item);
    }

    /**
     * Defines whether to support automatic sorting functions or not
     *
     * @param boolean $show
     */
    public function setOrder($show)
    {
        self::setSessionValue('order', $show, $this->item);
    }

    public static function getOrder($itemForce)
    {
        return self::getSessionValue('order', $itemForce);
    }

    /**
     * Returns whether to show the update button or not
     *
     * @return whether to show the update button or not
     *
     */
    public function getUpdateButton($itemForce = null)
    {
        $value = self::getSessionValue('updateButton', $itemForce);
        return $value;
    }

    /**
     * Whether to add automatic add and clear buttons
     *
     * @param boolean $showButtons true to add automatic add and clear buttons
     */
    public function setShowButtons($showButtons)
    {
        $this->showButtons = $showButtons;
    }

    /**
     * Returns whether to add automatic buttons
     *
     * @return boolean
     */
    public function getShowButtons()
    {
        return $this->showButtons;
    }

    /**
     * Make the table flow inside a div.
     *
     * @param integer $width in pixels (without 'px');
     * @param string  $type the type of overflow, see CSS overflow declaration
     */
    public function setOverFlowWidth($width, $type = 'auto')
    {
        $this->overflowWidth = $width;
        $this->overflowType = $type;
    }

    /**
     * Make the table flow inside a div.
     *
     * @param integer $width in pixels (without 'px');
     * @param string  $type the type of overflow, see CSS overflow declaration
     */
    public function setOverFlowHeight($height, $type = 'auto')
    {
        $this->overflowHeight = $height;
        $this->overflowType = $type;
    }

    /**
     * Automatically defined by the subdetail in parseFields when it detects a subdetail inside it.
     *
     * @param String $name name/item/id of the subdetail
     * @param String $childId name/item/id of the child
     */
    function addChild($name, $childId)
    {
        if ( $name && $childId )
        {
            $childs = self::getChilds($name);
            $childs[$childId] = $childId;
            self::setSessionValue('childs', $childs, $name);
        }
    }

    /**
     * Returns an array with the child ids
     *
     * @param String $name
     * @return String an array with the child ids
     */
    function getChilds($name)
    {
        return self::getSessionValue('childs', $name);
    }

    /**
     * Iterates through the field (or fields recursively) detecting whether inner fields exist or not
     * Function used to detect field relationships for JavaScript, and also parses and adjusts necessary fields
     *
     * @param Object $field
     * @param boolean $session
     * @param String $itemForce
     * @param boolean $neverEnterSessionIf
     * @return Object or array of objects
     */
    private function parseFields($field, $session = true, $itemForce = null, $neverEnterSessionIf = false)
    {
        if ( is_array($field) )
        {
            foreach ( $field as $line => $info )
            {
                $field[$line] = $this->parseFields($info);
            }
        }
        else
        if ( $field instanceof MSubDetail )
        {
            $this->namesArray[] = $this->item . '_' . $field->name;
            $this->classesArray[] = get_class($field); //duplicate because namesArray is also duplicate
            //does not parse the fields if it is a subdetail, the child subdetail does its own parsing
            self::addChild($this->item, $field->name); //adds a child to the subdetail
        }
        else
        if ( $field instanceof MTabbedBaseGroup )
        {
            //parses the internal fields but does not change the id of these first ones
            $this->parseFields($field->getControls());
        }
        else
        if ( ($field instanceof MContainer ) )
        {
            $field->setControls($this->parseFields($field->getControls()));
        }
        else
        if ( ($field instanceof MDiv ) )
        {
            $field->setInner($this->parseFields($field->getInner()));
        }
        else
        if ( ($field instanceof MTableRaw ) )
        {
            $field->setInner($this->parseFields($field->array));
        }
        
        /*
         * The $field variable is not always an object, sometimes it is an array, sometimes a string.
         * This causes many problems in the following lines, where it is simply assumed
         * to always be an object...
         * The problem is that we cannot ignore everything below when $field is not an object,
         * as it may break some behavior, so each notice must be handled individually...
         */

        //does not include the situations below in the field list
        if ( (is_object($field) && $field->name) && (!$field instanceof MContainer || $field instanceof MRadioButtonGroup) && !$field instanceof MLabel && !$field instanceof MText && !$field instanceof MSubDetail && !$field instanceof MRadioButton )
        {
            $this->namesArray[] = $this->item . '_' . $field->name; //adds the field with the subdetail name concatenated
            $this->classesArray[] = get_class($field); //duplicate because namesArray is also duplicate
            //reads the field default value and sets it as the component default value in the subdetail // defaultValue ;
            if ( $field->id && $field->value )
            {
                if ( $field instanceof MCheckBox || $field instanceof MCheckBox )
                {
                    self::setDefaultValue($field->id, $field->checked);
                }
                else
                {
                    self::setDefaultValue($field->id, $field->value);
                }
            }
        }

        if ( !$field instanceof MTabControl && !$field instanceof MTab && is_object($field) )
        {
            $field->name = $this->item . '_' . $field->name;
            $field->id = $this->item . '_' . $field->id;
        }

        if ( $field instanceof MLookupTextField )
        {
            $related = explode(',', $field->related);

            foreach ( $related as $rel )
            {
                $newRel[] = $this->item . '_' . $rel;
            }

            $field->related = implode(',', $newRel);

            if ( !is_array($field->filter) )
            {
                $field->filter = explode(',', $this->item . '_' . $field->filter);
            }

            $field->setContext($field->baseModule, $field->module, $field->item, $field->lookupEvent, $field->related, $field->filter, $field->autocomplete, $field->title);
            $field->lookup_name = "lookup_{$field->formId}_{$field->name}";
        }

        //put the fields in the session
        if ( !$neverEnterSessionIf )
        {
            if ( $session || ($field instanceof MSelection) || ($field instanceof MRadioButtonGroup) || ($field instanceof MCheckBoxGroup) )
            {
                // When $field is not an object, it is literally passing null to getColumn()...
                $fieldId = isset($field->id) ? $field->id : null;
                $column = self::getColumn($fieldId, $itemForce);

                // Depending on when this method is called, the columns may not yet exist in the session.
                // Therefore, it is necessary to first check if the object exists before checking the order attribute.
                if ( (isset($column->order) && $column->order) || ($field instanceof MSelection) || ($field instanceof MRadioButtonGroup) || ($field instanceof MCheckBoxGroup) || ($field instanceof MLookupTextField) )
                {
                    $newField = new StdClass();
                    $newField->id = $field->id;
                    $newField->name = $field->name;
                    $newField->label = $field->label;
                    $newField->class = strtolower(get_class($field));

                    if ( $field->options )
                    {
                        $newField->options = $field->options;
                    }

                    if ( $field instanceof MLookupTextField )
                    {
                        $newField->module = $field->module;
                        $newField->baseModule = $field->baseModule;
                        $newField->item = $field->item;
                        $newField->related = $field->related;
                        $newField->filter = $field->filter;
                    }

                    $fields = self::getSessionValue('fields', $this->item);
                    $fields[$field->name] = json_encode($newField); // was serialized and encoded for sending to the session, any other way does not work (does not reassemble in getFields)


                    self::setSessionValue('fields', $fields, $this->item);
                }
            }
        }

        //adds * for required fields inside the subdetail
        if ( $field instanceof MContainer )
        {
            $containerControls = $field->getControls();
            $validatorId = isset($containerControls[1]->id) ? $containerControls[1]->id : null;
            $valid = self::getValidator($this->item, $validatorId);

            if (isset($valid->type) && $valid->type == 'required')
            {
                $subContainer = $containerControls[0];

                if ( $containerControls[0]->inner && is_object($containerControls[0]->inner) )
                {
                    $containerControls[0]->inner->setClass('mCaptionRequired');
                }
            }
        }
        else
        {
            // Again, $field is not always an object...
            if (is_object($field))
            {
                $fieldIdName = $field->name ? $field->name : $field->id;
            }
            else
            {
                $fieldIdName = null;
            }
            
            $valid = self::getValidator($this->item, $fieldIdName);

            if ( isset($valid->type) && $valid->type == 'required' )
            {
                /*
                 * Apparently no field has the validator object already defined,
                 * giving a "Creating default object from empty value" warning
                 * in all cases. To avoid this and not break any possible
                 * logic that depends on type='required', create a stdClass in the attribute.
                 */
                if (!is_object($field->validator))
                {
                    $field->validator = new stdClass;
                }
                
                $field->validator->type = 'required';
            }
        }

        return $field;
    }

    public function getFields($itemForce, $elementId = null)
    {
        $fields = self::getSessionValue('fields', $itemForce);

        if ( is_array($fields) )
        {
            foreach ( $fields as $line => $info )
            {
                $data = json_decode($info, true);
                if ($data === null && $info !== 'null') {
                    // Fallback for old serialized data
                    $data = @unserialize($info, ['allowed_classes' => false]);
                }
                $field = (object) $data;

                $result[$field->id] = $field;

                if ( $elementId && $elementId == $field->id )
                {
                    return $field;
                }
            }
        }
        if ( !$elementId )
        {
            return $result;
        }
    }
    
    /**
     *
     * @return array
     */
    public function getCurrentFields()
    {
        return $this->currentFields;
    }

    /**
     * Defines the fields that will be used in the class
     *
     * @param array $fields array of objects
     */
    public function setFields($fields)
    {
        if ( $fields )
        {
            $this->currentFields = $fields;
            
            $fields = $this->parseFields($fields);
            $this->setControls($fields);
            $fieldNames = $this->namesArray;
            self::setSessionValue('fieldNames', $fieldNames, $this->item);
            $classesNames = $this->classesArray;
            self::setSessionValue('classesNames', $classesNames, $this->item);
        }
    }
    
    /**
     *
     * @return array
     */
    public function getCurrentColumns()
    {
        return $this->currentColumns;
    }

    /**
     * Defines the columns that will be created in the Table
     *
     * @param array $columns array of MGridColumn objects
     */
    public function setColumns($columns)
    {
        $this->currentColumns = $columns;

        $tempColumns = null;
        if ( $columns && is_array($columns) )
        {
            //create the titles and columns in the session
            foreach ( $columns as $info )
            {
                $info instanceof MGridColumn;
                
                if ( $info->visible == true )
                {
                    $titles[] = $info->title;
                    $temp = new StdClass();
                    $temp->align = $info->align;
                    $temp->title = $info->title;
                    $temp->width = $info->width;
                    $temp->visible = $info->visible;                    
                    $temp->options = $this->item . '_' . $info->options; //concatenate the subdetail name to the column
                    $temp->order = $info->order;
                    $temp->key = $info->options;
                    $temp->replace = $info->getReplace();
                    $tempColumns[] = $temp;
                }
            }
        }

        self::setSessionValue('titles', $titles, $this->item);
        self::setSessionValue('columns', $tempColumns, $this->item);
    }

    /**
     * Return a array of columns
     * You can use in static or object way
     *
     * @param String $itemForce the id of the subDetail
     * @return Return a array of columns
     */
    public function getColumns($itemForce = null)
    {
        return self::getSessionValue('columns', $itemForce);
    }

    public function getColumn($columnId, $itemForce = null)
    {
        $columns = self::getColumns($itemForce);

        if ( is_array($columns) )
        {
            foreach ( $columns as $info )
            {
                if ( $info->options == $columnId )
                {
                    return $info;
                }
            }
        }

        return false;
    }

    /**
     * addData a column to this table.
     *
     *
     * @param title   = inplemented
     * @param align   = inplemented
     * @param nowrap  = no
     * @param width   = inplemented
     * @param visible = inplemented
     * @param options = inplemented
     * @param order   = no
     * @param filter  = no
     *
     */
    private function addColumn($title, $align = 'left', $nowrap = 'notImplemented', $width = NULL, $visible = TRUE, $options = NULL, $order = 'notImplemented', $filter = 'notImplemented')
    {
        $titles = self::getSessionValue('titles', $this->item);
        $columns = self::getSessionValue('columns', $this->item);

        //Insert in the session as well
        $columns[] = new MGridColumn($title, $align, $nowrap, $width, $visible, $options, $order, $filter);

        if ( $visible == true )
        {
            $titles[] = $title;
        }

        self::setSessionValue('titles', $titles, $this->item);
        self::setSessionValue('columns', $columns, $this->item);
    }

    /**
     * Set the validators
     *
     * @param $validators
     */
    public function setValidators($validators = null)
    {
        $this->currentValidators = $validators;
        
        //clear the session if null
        if ( is_null($validators) )
        {
            self::setSessionValue('validators', '', $this->item);
            return;
        }

        //convert to array if a single object is passed
        //prevents blank screen error
        if ( is_object($validators) )
        {
            $validators = array( $validators );
        }

        if ( is_array($validators) )
        {
            foreach ( $validators as $line => $info )
            {
                $validators[$line] = $this->convertValidator($info);
            }
        }

        if ( is_array($validators) )
        {
            foreach ( $validators as $line => $info )
            {
                $validators[$line]->field = $this->item . '_' . $validators[$line]->field;
            }
        }

        if ( is_array($validators) )
        {
            self::setSessionValue('validators', $validators, $this->item);
        }
    }

    protected function convertValidator($validator)
    {
        $valid = new StdClass();
        $valid->id = $validator->id;
        $valid->field = $validator->field;
        $valid->min = $validator->min;
        $valid->max = $validator->max;
        $valid->type = $validator->type;
        $valid->chars = $validator->chars;
        $valid->mask = $validator->mask;
        $valid->checker = $validator->checker;
        $valid->msgerr = $validator->msgerr;
        $valid->html = $validator->html;
        $valid->label = $validator->label;
        $valid->value = $validator->value;
        $valid->hint = $validator->hint;
        $valid->regexp = $validator->regexp;
        $valid->expression = $validator->expression;
        $valid->class = get_class($validator);

        return $valid;
    }

    /**
     * Adds a custom action to the table
     */
    public function addAction($phpFunction, $imgUrl)
    {
        $action = new StdClass();
        $action->event = "AddTableResult" . $this->item;
        $action->jsFunction = "AddTableResult" . $this->item;
        $action->phpFunction = $phpFunction;
        $action->img = $imgUrl;
        $actions = self::getSessionValue('actions', $this->item);
        $actions[$phpFunction] = $action;
        self::setSessionValue('actions', $actions, $this->item);
    }

    /**
     * Generates an action string
     *
     * @return the html corresponding to an action
     */
    protected function generateActionString($i, $itemForce = NULL)
    {
        $MIOLO = MIOLO::getInstance();

        if ( isset($this) )
        {
            $item = $this->item;
        }
        if ( $itemForce )
        {
            $item = $itemForce;
        }

        $actions = self::getSessionValue('actions', $item);

        $tempString = null;
        $result = null;

        $actionColumWidth = 0;

        if ( $actions )
        {
            foreach ( $actions as $line => $info )
            {
                $link = null;
                $linkImg = null;
                $link = 'javascript:' . MUtil::getAjaxAction($info->phpFunction, array( 'mSubDetail' => $item, "arrayItemTemp{$item}" => $i ));
                $linkImg = new MImageLink("link$i", null, $link, $info->img);
                $result .= $linkImg->generate();

                if ( $MIOLO->checkMobile() )
                {
                    $linkImg->addStyle('padding', '5px');
                    $actionColumWidth += self::BIGIMGWIDTH;
                }
                else
                {
                    $actionColumWidth += self::IMGWIDTH;
                }
            }
        }
        $div = new MDiv('action' . rand(), $result);
        $div->addStyle('width', $actionColumWidth . 'px');

        return $div->generate();
    }

    /**
     * Processes the data according to the situation, removing or adding the component id in front of the data.
     *
     * @param Object $data data to be parsed
     * @param String $itemForce subdetail id
     * @param Boolean $remove whether to remove (otherwise it is addition)
     * @return Object
     */
    public function parseData($data, $itemForce, $remove = false)
    {
        $result = array();
        
        if ( is_array($data) )
        {
            foreach ( $data as $line => $info )
            {
                $newData = array( );
                $infoArray = (array) $info;
                
                // $info can be an array, but it is always used as an object in this
                // method. Cast to ensure the type and not emit a warning
                // in get_class().
                $info = (object) $info;

                foreach ( $infoArray as $l => $i )
                {

                    if ( $l == 'dataStatus' )
                    {
                        $newData[$l] = $i;
                    }

                    $pos_underline = stripos($l, '_');

                    if ( strrpos($l, "\0") > 0 )
                    {
                        //FIXME: Workaround to map private fields of the class
                        //http://br.php.net/manual/en/language.types.array.php
                        $aux = explode("\0", $l);
                        $l = $aux[2];
                    }

                    if ( !is_object($i) )
                    {
                        //if adding
                        if ( !$remove )
                        {
                            if ( $pos_underline > 0 )
                            {
                                if ( method_exists(get_class($info), "obter$l") )
                                {
                                    $function = 'obter' . $l;
                                    $newData[$l] = $info->$function();
                                }
                                else
                                {
                                    $newData[$l] = $i;
                                    $newData[$itemForce . '_' . $l] = $i; // this was added for MLookupContainer inside MSubDetail, to make the _lookDescription field work
                                }
                            }
                            else
                            {
                                if ( method_exists(get_class($info), "obter$l") )
                                {
                                    $function = 'obter' . $l;
                                    $newData[$itemForce . '_' . $l] = $info->$function();
                                }
                                else
                                {
                                    $newData[$itemForce . '_' . $l] = $i;
                                }
                            }
                        }
                        else //if removing
                        {
                            if ( $pos_underline > 0 )
                            {
                                $temp = explode('_', $l);

                                if ( isset($temp[2]) && $temp[2] ) //this is done because the lookup has an _ in its name, but MSubdetail needs to get the full name
                                {
                                    $temp[1] .= '_' . $temp[2];
                                }

                                $index = $temp[1] ? $temp[1] : $temp[0];

                                if ( method_exists(get_class($info), "obter$index") )
                                {
                                    $function = 'obter' . $index;
                                    $newData[$index] = $info->$function();
                                }
                                else
                                {
                                    if ( !isset($newData[$index]) || !is_array($newData[$index]) )
                                    {
                                        $newData[$index] = $i;
                                    }
                                }
                            }
                            else
                            {
                                if ( method_exists(get_class($info), "obter$l") )
                                {
                                    $function = 'obter' . $l;
                                    $newData[$l] = $info->$function();
                                }
                                else
                                {
                                    // safety to not overwrite already existing data,
                                    // useful in the case of internal subdetail, in other cases it does not interfere
                                    if ( !isset($newData[$l]) || !is_array($newData[$l]) )
                                    {
                                        $newData[$l] = $i;
                                    }
                                }
                            }
                        }
                    }
                }

                $result[] = (object) $newData;
            }
        }

        if ( !$remove )
        {
            return self::parseFieldData($result, $itemForce);
        }

        return $result;
    }

    /**
     * Processes the data to automatically recognize the fieldDescription among others
     *
     * The data must already come processed with the subdetail name in front, hence $result.
     *
     * @param $result
     * @return unknown_type
     */
    public function parseFieldData($result, $itemForce)
    {
        if ( is_array($result) )
        {
            $fieldNamesD = self::getSessionValue('fieldNames', $itemForce);
            $classesNamesD = self::getSessionValue('classesNames', $itemForce);

            if ( $fieldNamesD )
            {
                foreach ( $fieldNamesD as $line => $info )
                {
                    $classesData[$info] = $classesNamesD[$line];
                }
            }

            foreach ( $result as $line => $info )
            {
                $array = (array) $info;
                $newData = null;

                foreach ( $fieldNamesD as $index => $fieldId )
                {
                    if ( !isset($array[$fieldId]) )
                    {
                        $array[$fieldId] = null; //FIXME would add the missing fields in the data, would make the 'Não' work in the checkbox case, but it is not working, it is clearing the fields in setData
                    }
                }

                foreach ( $array as $fieldId => $value )
                {
                    $valueDescription = null; //Reset the variable inside the foreach
                    $field = self::getFields($itemForce, $fieldId);
                    $phpClasse = isset($classesData[$fieldId]) ? $classesData[$fieldId] : null;
                    $fieldIdDescription = $fieldId . 'Description';

                    if ( $phpClasse == 'MCheckBox' )
                    {
                        $newData[$fieldIdDescription] = _M('Não');

                        if ( isset($value) )
                        {
                            $newData[$fieldIdDescription] = _M('Sim');

                            if ( is_string($value) && $value == DB_FALSE )
                            {
                                $newData[$fieldIdDescription] = _M('Não');
                            }
                        }
                    }

                    /*
                     * Same problem as the parseFields() method:
                     * $field here can be NULL or stdClass, so it causes many notices in the lines below.
                     * To not break any existing behavior, handle each case individually.
                     */
                    
                    if (isset($field->options))
                    {
                        $options = $field->options;

                        // search for the description string of the current data in the grid
                        if ($value)
                        {
                            // if the options are a multi-dimensional array
                            if (isset($options[0]) && is_array($options[0]))
                            {
                                foreach ($options as $index => $option)
                                {
                                    if (isset($option[0]) && $option[0] == $value)
                                    {
                                        $valueDescription = $option[1];
                                    }
                                }
                            }
                            else //if it is a simple array
                            {
                                $valueDescription = $field->options[$value];
                            }

                            // only set the description data if they do not already exist
                            if (!array_key_exists($fieldIdDescription, $newData))
                            {
                                $newData[$fieldIdDescription] = $valueDescription;
                            }
                        }
                    }

                    // if it is a description field and does not contain data, clear the description to avoid showing '--Select--' in the data table
                    if ( strpos($fieldId, 'Description') && !strpos($fieldId, 'lookup') )
                    {
                        $realFieldId = str_replace('Description', '', $fieldId);

                        if ( !$result[$line]->$realFieldId )
                        {
                            $newData[$fieldId] = null;
                        }
                        else
                        {
                            $newData[$fieldId] = $value;
                        }
                    }
                    else
                    {
                        //set the default data
                        $newData[$fieldId] = $value;
                    }
                }

                $result[$line] = (object) $newData;
            }
        }

        return $result;
    }

    /**
     * Custom generate to this class it implements some MGridColumn function:
     * List:
      $   title   = inplemented
      $   align   = inplemented
      $   nowrap  = no
      $   width   = inplemented
      $   visible = inplemented
      $   options = inplemented
      $   order   = no
      $   filter  = no
     */
    public function generate()
    {
        $module = MIOLO::getCurrentModule();
        $MIOLO = MIOLO::getInstance();

        $item = $this->item;
        //get the class name of the current form (which is the subdetail's parent)
        self::setSessionValue('form', get_class($this->parent), $item);

        $sendArray['mSubDetail'] = $item;

        //function called only first time that component is rendered
        //mount buttons
        if ( $this->readonly )
        {
            $this->setControls(null);
        }

        $this->addControl(new MDiv("divErrorValidators", array()));
        
        if ( $this->showButtons && !$this->readonly )
        {
            $addImg = $MIOLO->getUI()->getImage(NULL, 'button_add.png');
            $updImg = $MIOLO->getUI()->getImage(NULL, 'button_apply.png');
            $clearImg = $MIOLO->getUI()->getImage(NULL, 'button_clear.png');

            $span = new MSpan('label_clearData', _M('Limpar'));
            $buttons[0] = new MButton('clearData' . $item, $span->generate(), MUtil::getAjaxAction('clearTableFields', $sendArray), $clearImg);

            if ( $this->updateButton )
            {
                $span = new MSpan('label_addData', _M('Adicionar'));
                $buttons[1] = new MButton('addData' . $item, $span->generate(), MUtil::getAjaxAction('forceAddToTable', $sendArray), $addImg);
                $span = new MSpan('label_updateData', _M('Atualizar'));
                $buttons[2] = new MButton('updateData' . $item, $span->generate(), MUtil::getAjaxAction('addToTable', $sendArray), $updImg);
            }
            else
            {
                $span = new MSpan('label_addData', _M('Adicionar'));
                $buttons[1] = new MButton('addData' . $item, $span->generate(), MUtil::getAjaxAction('addToTable', $sendArray), $addImg);
            }

            $divButtons = new MDiv('divButtonsSubdetail', $buttons, 'mSubDetailButtons', array('style' => 'text-align: center'));
            $this->addControl($divButtons);
        }

        $div = new MDiv('div' . $item, self::getTable($item, $this->readonly));

        if ( $this->overflowWidth )
        {
            $div->addStyle('overflow-x', $this->overflowType);
            $div->addStyle('width', $this->overflowWidth . 'px');
        }
        else
        {
            $div->addStyle('width', '100%');
        }

        if ( $this->overflowHeight )
        {
            $div->addStyle('overflow-y', $this->overflowType);
            $div->addStyle('height', $this->overflowHeight . 'px');
        }

        $this->controls->add($div);

        $index = new MTextField('mSubdetail[]', $item, '');
        $index->addStyle('display', 'none');
        $this->controls->add($index);

        $this->caption = $this->title;

        return parent::generate();
    }

    public function order($columnName, $orderType = null, $itemForce = null)
    {
        //try to get the id from this if itemForce is not passed
        if ( isset($this) && !$itemForce )
        {
            $itemForce = $this->item;
        }

        //get the subdetail data
        $itensData = self::getData($itemForce, false);

        //if there is no sort type, do automatic sorting, which always inverts the current sort order
        if ( !$orderType & !is_array($columnName) )
        {
            $orderType = self::getSessionValue('orderType_' . $columnName, $itemForce);

            if ( !$orderType )
            {
                $orderType = 'desc';
            }

            $orderType = $orderType == 'asc' ? 'desc' : 'asc';

            //set the orderType data in the session
            self::setSessionValue('orderType_' . $columnName, $orderType, $itemForce);
        }

        //define the sorted columns in the session
        self::setSessionValue('orderColumnName', $columnName, $itemForce);

        //transform columnName into array if it is not one
        if ( !is_array($columnName) )
        {
            $columnName = array( $columnName );
        }

        if ( !is_array($orderType) )
        {
            $orderType = array( $orderType );
        }

        //add the subdetail name to the column if necessary
        foreach ( $columnName as $line => $info )
        {
            if ( stripos($info, '_') === false )
            {
                $columnName[$line] = $itemForce . '_' . $info;
            }
        }

        $orderArray = array( );

        //build sorting arrays
        if ( is_array($itensData) )
        {
            foreach ( $itensData as $line => $info )
            {
                foreach ( $columnName as $index => $name )
                {
                    //get by description, in case of selections, checkbox and radios ...
                    $key = $info->{$name . 'Description'};

                    //  if not found by description, get the normal field data
                    if ( !$key )
                    {
                        $key = $info->$name;
                    }

                    $key = strtoupper($key); // convert everything to uppercase to not interfere with sorting
                    $orderArray[$name][] = $key;
                }

                $newItensData[] = $info; //add to the new array
            }
        }

        foreach ( $columnName as $line => $name )
        {
            $funcArgs[] = $orderArray[$name];
            $funcArgs[] = strtolower($orderType[$line]) == 'desc' ? SORT_DESC : SORT_ASC;
            ;
        }

        $funcArgs[] = $newItensData;

        call_user_func_array('array_multisort', $funcArgs);

        $itensData = array( );

        //rebuild the original array with numbered indices
        if ( is_array($newItensData) )
        {
            $i = 0;
            foreach ( $newItensData as $line => $info )
            {
                $itensData[$i] = $info;
                $i++;
            }
        }

        //get and define images to be used
        $MIOLO = MIOLO::getInstance();
        $ui = $MIOLO->getUI();
        $upImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'down.png');
        $downImg = $ui->getImageTheme($MIOLO->getTheme()->getId(), 'up.png');

        //add the sorting arrows via javascript

        foreach ( $columnName as $line => $info )
        {
            $img = $orderType[$line] == 'asc' ? $upImg : $downImg;

            $MIOLO->page->onload("
			    newImg = document.createElement('img');
			    newImg.src= '$img';
			    newImg.id = 'order_{$columnName[$line]}_img';
			    newImg.style.marginLeft = '10px';

			    if (element = document.getElementById('order_{$columnName[$line]}'))
			    {
			        element.parentNode.appendChild( newImg );
			    }
	        ");
        }

        //update the subdetail via ajax
        self::update($itensData, $itemForce);
    }

    public function hasData($item = null)
    {
        if ( $item )
        {
            return count(self::getData($item)) > 0;
        }
        else
        {
            return count($this->getData()) > 0;
        }

        return false;
    }

    public function setValue($data)
    {
        if ( !self::isFirstAccess() || !$data )
        {
            return;
        }

        $data = self::parseData($data, $this->item);
        $controls = self::getFields($this->item);

        // Check attribute that indicates whether the lookup autocomplete should be triggered
        if ( self::$doLookupAutocomplete )
        {
            // Trigger the autocomplete of lookups present in the subdetail
            $data = self::lookupAutocomplete($data, $controls);
        }

        self::clearData($this->item);
        self::addData($data, $this->item);
        self::getData($this->item, false); // Called to reorder the indices
    }

    public function getValue($value)
    {
        self::getData($this->item);
    }

    public static function getTable($item, $readOnly = false)
    {
        $titles = self::getSessionValue('titles', $item);
        $columns = self::getSessionValue('columns', $item);
        $sessionActions = self::getSessionValue('actions', $item);

        //transforms the titles into links
        if ( self::getOrder($item) && !$readOnly )
        {
            foreach ( $titles as $line => $info )
            {
                if ( $columns[$line]->order )
                {
                    $link = MUtil::getAjaxAction('order', array( 'mSubDetail' => $item, 'mSubDetailOrderField' => $columns[$line]->options ));
                    $titles[$line] = new MLinkButton('order_' . $columns[$line]->options, $info, $link);
                }
            }
        }

        //add actions column if not read-only and has actions
        if ( is_array($sessionActions) && !$readOnly )
        {
            $titles = array_merge(array( _M('Ações') ), $titles);

            $temp = new StdClass();
            $temp->align = 'left';
            $temp->title = _M('Ações');
            $temp->width = '';
            $temp->visible = true;
            $temp->options = '';

            $columns = array_filter($columns);
            $columns = array_merge(array( $temp ), $columns); //limpa
        }

        $table = new MTableRaw('', array( ), $titles);
        $table->setAlternate(true);
        $table->addAttribute('width', '100%');
        $table->addStyle('width', '100%');
        $table->addStyle('width', '100%');
        $table->addAttribute("Style", "width:100%");
        $table->setCellAttribute(0, 0, "width", '1%');

        $itens = self::getData($item, false);

        //build the data for the table
        $tableData = array();
        if ( $itens )
        {
            foreach ( $itens as $i => $info )
            {
                /*
                 * In some circumstances, such as when opening a form for the first time, items do not have
                 * the "dataStatus" attribute, emitting a notice when making the comparison.
                 * To fix it simply, invert the comparison logic and ignore all
                 * items with self::STATUS_REMOVE:
                 * if ( $info->dataStatus != self::STATUS_REMOVE )
                 */
                if (isset($info->dataStatus) && $info->dataStatus == self::STATUS_REMOVE)
                {
                    continue;
                }
                
                if ( !$readOnly )
                {
                    $actions[$i] = self::generateActionString($i, $item);
                }
                $args = null;
                if ( $sessionActions )
                {
                    if ( !$readOnly )
                    {
                        $actions[$i] = self::generateActionString($i, $item);
                        $args[] = $actions[$info->arrayItem];
                    }
                }
                foreach ( $columns as $line => $column )
                {
                    $column instanceof MGridColumn;

                    if ( $column->visible == true )
                    {
                        if ( $column->options )
                        {
                            //get by description if it exists
                            $opt = $column->options;
                            $tempOpt = $opt . 'Description';
                            if (isset($info->$tempOpt))
                            {
                                $columnData = $info->$tempOpt;
                            }
                            else
                            {
                                $columnData = $info->$opt;
                            }

                            $replace = $column->replace[$column->key];
                            if ( $replace )
                            {
                                $columnData = $replace[$info->$opt];
                            }

                            if ( is_array($columnData) )
                            {
                                $columnData = '<pre>' . print_r($columnData, 1) . '</pre>';
                            }

                            $args[] = $columnData;

                            $cellId = $column->options . '_' . $i;

                            $aplyCellAttributeId = $i;

                            $table->setCellAttribute($aplyCellAttributeId, $line, 'id', $cellId);
                            //mSubDetailCellEditId
                            if ( $column->order && !$readOnly )
                            {
                                $table->setCellAttribute($aplyCellAttributeId, $line, 'onclick', MUtil::getAjaxAction('editCell', array( 'mSubDetail' => $item, 'mSubDetailCellEditId' => $cellId )));
                            }

                            //alignment
                            if ( $column->align )
                            {
                                $table->setCellAttribute($aplyCellAttributeId, $line, 'align', $column->align);
                            }
                            //size
                            if ( $column->width )
                            {
                                $table->setCellAttribute($aplyCellAttributeId, $line, 'width', $column->width);
                            }
                        }
                    }
                }

                $tableData[] = $args;
            }
        }

        $table->array = $tableData; //set the data in the array

        return $table;
    }

    /**
     * Update the visual Component with some data. Make a ajax response
     *
     * @param array $data array of object
     * @param string $itemForce the name of the table
     */
    public static function update($data = NULL, $name)
    {
        $MIOLO = MIOLO::getInstance();
        self::setData($data, $name);
        $MIOLO->ajax->setResponse(self::getTable($name), 'div' . $name);
    }

    public static function removeFromTable($args)
    {
        $arrayItem = 'arrayItemTemp' . $args->mSubDetail;
        $MIOLO = MIOLO::getInstance();
        $indexData = self::getData($args->mSubDetail, false); //session data
        $lineData = $indexData[$args->$arrayItem]; //session data for the current row

        if (isset($lineData->dataStatus) && $lineData->dataStatus == self::STATUS_ADD)
        {
            self::removeData($args->$arrayItem, $args->mSubDetail);
        }
        else
        {
            $itensData = self::getSessionValue('contentData', $args->mSubDetail);
            $itensData[$args->$arrayItem]->dataStatus = self::STATUS_REMOVE;
            self::setSessionValue('contentData', $itensData, $args->mSubDetail);
        }

        self::update(null, $args->mSubDetail);
    }

    public static function forceAddToTable($args)
    {
        unset($args->arrayItemTemp[$args->mSubDetail]);
        self::addToTable($args);
    }

    public static function addToTable($data)
    {
        $subdetailId = $data->mSubDetail;
        $arrayItem = 'arrayItemTemp' . $data->mSubDetail;
        $childs = self::getChilds($subdetailId);

        //detect the children and capture their data
        if ( is_array($childs) )
        {
            foreach ( $childs as $line => $childId )
            {
                if ( $childId != $data->mSubDetail ) // if it is not the current subdetail to avoid recursion
                {
                    $innerData = self::getData($childId);
                    $data->$childId = $innerData; //get the child mSubdetail data and add to the parent
                    self::clearData($childId);
                    self::update(null, $childId); //clear the child and update
                }
            }
        }

        $temp = null;
        $module = MIOLO::getCurrentModule();
        $validators = self::getValidators($subdetailId);
        $errors = array();

        foreach ( $validators as $validator )
        {
            $field = $validator->field;
            $fieldWithoutSub = substr($field, strpos($field, "_") + 1);
            $valid = MUtil::NVL($validator->label, $field);

        if ( !(strlen(MIOLO::_REQUEST($field)) > 0) && $validator->type == 'required' )
        {
            $errors[] = _M('O campo "@1" é obrigatório!', MIOLO::getCurrentModule(), $valid);
        }

            if ( $validator->checker == 'unique' && $data->$field )
            {
                $tempData = MSubDetail::getData($data->mSubDetail);
                
                if ( is_array($tempData) && $tempData )
                {
                    foreach ( $tempData as $l => $i )
                    {
                        if ( !$i->removeData && 
                             ($i->$fieldWithoutSub == MIOLO::_REQUEST($field) && ($i->arrayItem <> $data->$arrayItem || !(strlen($data->$arrayItem) > 0))))
                        {
                            // If is the edited line, it is not duplicating.
                            if ( is_null($data->arrayItemTemp) || ($data->arrayItemTemp != $i->arrayItem) )
                            {
                                $errors[] = _M('O campo "@1" deve ser um valor único.', MIOLO::getCurrentModule(), $valid);
                            }
                        }
                    }
                }
            }
            
        }

        if ( count($errors) > 0 )
        {
            self::setValidationError(implode('<br>', $errors));
            return;
        }
        elseif ( is_object($data) )
        {
            self::clearValidationErrors();
        }
        
        //if editing
        if ( $data->$arrayItem || $data->$arrayItem === '0' )
        {
            //get the current data from the session
            $sData = self::getDataItem($data->$arrayItem, $subdetailId);
            //and set the dataStatus from the session for the current record
            $data->dataStatus = isset($sData->dataStatus) ? $sData->dataStatus : null;

            //if the status is different from add, change to edited
            if ( $data->dataStatus != self::STATUS_ADD )
            {
                $data->dataStatus = self::STATUS_EDIT;
            }
            //update the specific data
            self::defineData($data->$arrayItem, $data, $data->mSubDetail);
        }
        //if adding
        else
        {
            $data->dataStatus = self::STATUS_ADD;
            self::addData($data, $data->mSubDetail);
        }

        //clear the fields and set focus on the first one
        self::clearFields($data->mSubDetail);

        self::update(null, $data->mSubDetail);
    }

    /**
     * Sets the focus on the first field of the subdetail
     *
     * @return unknown_type
     */
    private static function setFocus($mSubDetailId)
    {
        $MIOLO = MIOLO::getInstance();
        $fieldNames = self::getSessionValue('fieldNames', $mSubDetailId);

        if ( $fieldNames[0] )
        {
            $fieldNameFocus = trim($fieldNames[0]);
            $MIOLO->page->onload("dojo.byId('$fieldNameFocus').focus();");
        }
    }
    
    private static function clearFields($name)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $addImg = $MIOLO->getUI()->getImage(NULL, 'button_add.png');
        $clearImg = $MIOLO->getUI()->getImage(NULL, 'button_clear.png');
        $fieldNames = self::getSessionValue('fieldNames', $name);
        $temp = '';

        //iterate through the fields setting them as empty
        if ( is_array($fieldNames) )
        {
            foreach ( $fieldNames as $line => $info )
            {
                $info = str_replace('Description', '', $info); //prevents clearing the content of $valueDescription
                $temp .= "msubdetail.updateField('$info','');";
            }
        }

        $temp .= "msubdetail.updateButtons( '$name', '$addImg', '$clearImg');";
        $temp .= "dojo.byId('arrayItemTemp{$name}').value=''"; //clear the arrayItem

        $MIOLO->page->onload($temp);

        self::setFocus($name);
    }

    /**
     * Clears the values filled in the fields
     *
     * @param object $args
     */
    public static function clearTableFields($args)
    {
        $childs = self::getChilds($args->mSubDetail);

        //detect the children and clear them as well
        if ( is_array($childs) )
        {
            foreach ( $childs as $line => $info )
            {
                if ( $info != $args->mSubDetail ) // if it is not the current subdetail to avoid recursion
                {
                    self::clearData($info);
                    self::update(null, $info); //limpa o filho e atualiza
                }
            }
        }

        self::clearFields($args->mSubDetail);

        self::update(null, $args->mSubDetail);
        
        self::clearValidationErrors();
    }

    public static function upFromTable($data)
    {
        $object = $data->mSubDetail;
        $data->sessionItem = $data->mSubDetail;
        $nivel = $data->arrayItem;

        if ( !$nivel )
        {
            $nivel = $data->arrayItemTemp[$args->mSubDetail];
        }
        if ( $nivel != 0 )
        {
            $tempObjAtual = self::getDataItem($nivel, $object);
            $tempObjSuperior = self::getDataItem($nivel - 1, $object);
            self::defineData($nivel - 1, $tempObjAtual, $object);
            self::defineData($nivel, $tempObjSuperior, $object);
        }

        self::update(null, $args->mSubDetail);
    }

    public static function downFromTable($data)
    {
        $object = $data->mSubDetail;
        $data->sessionItem = $data->mSubDetail;
        $nivel = $data->arrayItem;

        if ( !$nivel )
        {
            $nivel = $data->arrayItemTemp[$args->mSubDetail];
        }

        $item = self::getData($object, false);

        if ( $nivel < count($item) - 1 )
        {
            $tempObjAtual = self::getDataItem($nivel, $object);
            $tempObjInferior = self::getDataItem($nivel + 1, $object);
            self::defineData($nivel + 1, $tempObjAtual, $object);
            self::defineData($nivel, $tempObjInferior, $object);
        }

        self::update(null, $args->mSubDetail);
    }

    /**
     * Function called automatically when pressing edit in the table, sets the field values
     *
     * @param object $data ajax miolo object
     */
    public static function editFromTable($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        self::moveDataToFields($args);
        $arrayItem = 'arrayItemTemp' . $args->mSubDetail;
        $updateButton = self::getUpdateButton($args->mSubDetail);
        $temp = '';

        if ( !$updateButton )
        {
            $imgAplicar = $MIOLO->getUI()->getImage(NULL, 'button_apply.png');
            $imgCancelar = $MIOLO->getUI()->getImage(NULL, 'button_cancel.png');
            $temp .= "msubdetail.updateButtons('$args->mSubDetail', '$imgAplicar', '$imgCancelar', 'aplicar');";
        }

        $temp .= "dojo.byId('{$arrayItem}').value='{$args->$arrayItem}'";

        //fake response just to execute the js
        $MIOLO->page->onload($temp);
        $MIOLO->ajax->setResponse(null, 'divResponse');
    }

    public function moveDataToFields($args)
    {
        $MIOLO = MIOLO::getInstance();
        $childs = self::getChilds($args->mSubDetail);
        $arrayItem = 'arrayItemTemp' . $args->mSubDetail;
        $temp = '';

        //detect children and set their data
        if ( is_array($childs) )
        {
            foreach ( $childs as $line => $childId )
            {
                if ( $childId != $args->mSubDetail ) // if it is not the current subdetail to avoid recursion
                {
                    $valuesSB = self::getDataItem($args->$arrayItem, $args->mSubDetail);
                    $subData = $valuesSB->$childId;

                    if ( !$subData )
                    {
                        $name = $args->mSubDetail . "_" . $childId;
                        $subData = $valuesSB->$name;
                    }

                    //if data still not found, clear the internal subdetail
                    if ( !$subData )
                    {
                        self::clearData($childId);
                    }

                    self::update($subData, $childId);
                }
            }
        }

        $module = MIOLO::getCurrentModule();
        $values = self::getDataItem($args->$arrayItem, $args->mSubDetail);
        $fieldNames = self::getSessionValue('fieldNames', $args->mSubDetail);

        if ( is_array($fieldNames) )
        {
            foreach ( $fieldNames as $line => $info )
            {
                $value = $values->$info;
                $value = str_replace("\n", '\n', $value);
                $value = str_replace("'", "\'", $value);
                $value = str_replace('"', "\"", $value);
                $temp .= "msubdetail.updateField('$info','$value');\n";
            }
        }

        $MIOLO->page->onload($temp);
    }

    public function duplicate($args)
    {
        $MIOLO = MIOLO::getInstance();
        self::moveDataToFields($args);
        $MIOLO->ajax->setResponse(null, 'divResponse');
    }

    /**
     * Event called when clicking on an editable cell
     */
    public function editCell($args)
    {
        $MIOLO = MIOLO::getInstance();
        $cellId = $args->mSubDetailCellEditId;
        $id = explode('_', $args->mSubDetailCellEditId);
        $arrayItem = $id[2];
        $id = $id[0] . '_' . $id[1];
        $field = self::getFields($args->mSubDetail, $id);
        $itemData = self::getDataItem($arrayItem, $args->mSubDetail);
        $value = $itemData->$id;

        if ( is_object($field) )
        {

            $idEx = $field->id . '_ex';
            $field->id = $idEx;
            $field->name .= '_ex';

            if ( $field->class == 'mtextfield' )
            {
                $field = new MTextField($field->name, $value);
            }
            else
            if ( $field->class == 'MSelection' || $field->class == 'mselection' )
            {
                $field = new MSelection($field->name, $value, null, $field->options);
            }

            $field = self::parseFields($field, false, $args->mSubDetail, true);

            $field->addStyle('width', '100%');
            $field->addStyle('height', '100%');
        }

        //add event on field exit, add style and set value
        $ajaxAction = MUtil::getAjaxAction('editCellExit', array( 'mSubDetail' => $args->mSubDetail, 'mSubDetailCellEditId' => $cellId ));
        $js .= "
        innerElement = dojo.byId('$idEx');

        if (innerElement)
        {
            blur = dojo.connect(innerElement, 'onblur', function () { $ajaxAction ; dojo.disconnect(blur); });
            keyPress = dojo.connect(innerElement, 'onkeypress', function (e) {if (e.keyCode == dojo.keys.ENTER){ $ajaxAction ; dojo.disconnect( keyPress ); }  });
            innerElement.focus();
        }";

        $MIOLO->page->onload($js);
        $MIOLO->ajax->setResponse($field, $cellId);
    }

    /**
     * Event executed when leaving an editable cell
     */
    public function editCellExit($args)
    {
        $cellId = $args->mSubDetailCellEditId;
        $id = explode('_', $args->mSubDetailCellEditId);
        $id[0] .= '_' . $id[1];
        $fieldId = $id[0] . '_ex';
        $fieldIdDesc = $id[0] . '_exDescription';
        $dataId = $id[0];
        $dataIdDesc = $id[0] . 'Description';
        $arrayItem = $id[2];

        $value = $args->$fieldIdDesc ? $args->$fieldIdDesc : $args->$fieldId;
        $value = addSlashes($value); //FIXME this does not work, it needs to work to accept single quotes


        $itemData = self::getDataItem($arrayItem, $args->mSubDetail);
        $itemData->$dataId = $args->$fieldId;
        $itemData->$dataIdDesc = $args->$fieldIdDesc;
        self::defineData($arrayItem, $itemData, $args->mSubDetail);

        $MIOLO = MIOLO::getInstance();
        $MIOLO->ajax->setResponse($value, $cellId);
    }

    /**
     * Clears the dataStatus field from all data of the given subdetail
     *
     * Function called after sending the SubDetail to the database, but continuing its use.
     *
     * If STATUS_REMOVE, it means it should be removed from the database; after removal, the record is removed from the subdetail
     * If STATUS_ADD, the record was inserted in the database and can remain here, but nothing else needs to be done, so we remove the dataStatus
     * The STATUS_EDIT case is the same as STATUS_ADD, the record was edited and we can remove the dataStatus
     *
     * @param $name
     * @return void
     */
    public static function clearDataStatus($name)
    {
        $data = self::getData($name);

        if ( is_array($data) )
        {
            foreach ( $data as $line => $info )
            {
                if ( $info->dataStatus == self::STATUS_REMOVE )
                {
                    unset($data[$line]);
                }
                else
                {
                    unset($data[$line]->dataStatus);
                }
            }
        }

        self::setData($data, $name);
    }

    public static function setSessionValue($var, $value, $item)
    {
        $MIOLO = MIOLO::getInstance();
        $session = $MIOLO->getSession();
        $itemName = MIOLO::getCurrentAction() . ':' . $item;
        $object = $session->getValue($itemName) ?: new stdClass;
        $object->$var = $value;
        //differentiate by handler
        $session->setValue($itemName, $object);
    }

    public static function getSessionValue($var, $item)
    {
        $MIOLO = MIOLO::getInstance();
        //differentiate by handler
        $object = $MIOLO->getSession()->getValue(MIOLO::getCurrentAction() . ':' . $item);
        
        return isset($object->$var) ? $object->$var : null;
    }

    /**
     * Get validators
     *
     * @return $validators (Array)
     */
    public static function getValidators($item)
    {
        return (array) self::getSessionValue('validators', $item);
    }
    
    /**
     * @return array
     */
    public function getCurrentValidators()
    {
        return $this->currentValidators;
    }

    public static function getValidator($item, $id)
    {
        $validators = self::getValidators($item);
        
        if ( strpos($id, $item . '_') !== 0 )
        {
            $id = $item . '_' . $id;
        }

        if ( is_array($validators) )
        {
            foreach ( $validators as $line => $valid )
            {
                if ( $valid->field == $id )
                {
                    return $valid;
                }
            }
        }
    }

    /**
     * getData Item all itens of table/session, organized with arrayItem
     * Each time u call this function the session is cleanned and rewrited
     */
    public static function getData($itemForce = NULL, $final = true)
    {
        if ( isset($this) )
        {
            $item = $this->item;
        }
        if ( $itemForce )
        {
            $item = $itemForce;
        }

        //get the required fields of the subdetail
        $fieldNames = self::getSessionValue('fieldNames', $item);
        $classesNames = self::getSessionValue('classesNames', $item);

        if ( $fieldNames )
        {
            foreach ( $fieldNames as $line => $info )
            {
                $info2 = str_replace('Description', '', $info);
                $fieldNames[] = $info2 . 'Description'; //add support for fieldDescription field (used in MSelection and MCheckBox)
                $classesData[$info2] = $classesNames[$line];

                $info3 = str_replace($itemForce . '_', '', $info); //remove the subdetail id from the name
                $fieldNames[] = $info3;
                $classesData[$info3] = $classesNames[$line];
            }

            $fieldNames[] = 'arrayItem';
            $fieldNames[] = 'dataStatus';
        }

        $itensData = self::getSessionValue('contentData', $item);

        self::setSessionValue('contentData', null, $item); // clearData realy need??
        //rewrite the session with correct arrayItem values
        $tempData = array( );

        if ( $itensData )
        {
            $x = 0;
            foreach ( $itensData as $line )
            {
                $newLine = new stdClass;

                $line->arrayItem = $x;
                $x++;

                foreach ( $line as $k => $l )
                {
                    //if there is a field name in the session, i.e., subdetail was already instantiated, filter the fields
                    if ( !$fieldNames || in_array($k, $fieldNames) )
                    {
                        // $classe not used?
                        //$classe = strtolower($classesData[$k]); // PHP class of the field

                        if (!isset($newLine->$k))
                        {
                            $newLine->$k = $l;
                        }
                    }
                }

                $tempData[] = $newLine;
            }
        }

        self::setSessionValue('contentData', $tempData, $item);

        if ( $final )
        {
            $tempData = self::parseData($tempData, $itemForce, true);
        }

        return $tempData;
    }

    /**
     * getDataItem one item from table
     *
     * @param arrayItem the index of the item you wanna take
     */
    public static function getDataItem($arrayItem, $item)
    {
        $itensData = self::getSessionValue('contentData', $item);

        if ( is_array($itensData) )
        {
            foreach ( $itensData as $line => $info )
            {
                if ( $info->arrayItem == $arrayItem )
                {
                    return $info;
                }
            }
        }
    }

    /**
     * Set one item to table
     *
     * @param arrayItem the index to be seted
     * @param $obj the object to put into table
     */
    public static function defineData($arrayItem, $data, $item)
    {
        $itensData = self::getSessionValue('contentData', $item);
        $parseData = array( $data );
        $data = self::parseFieldData($parseData, $item);
        $itensData[$arrayItem] = $data[0];
        self::setSessionValue('contentData', $itensData, $item);
    }

    /**
     * addData some item to session/Table (You can pass an array or one item)
     * It is a recursive function.
     */
    public static function addData($data, $item)
    {
        if ( $data )
        {
            if ( is_array($data) )
            {
                foreach ( $data as $line => $info )
                {
                    self::addData($info, $item);
                }
            }
            else
            {
                $itensData = self::getSessionValue('contentData', $item);
                $parseData = array( $data );
                $data = self::parseFieldData($parseData, $item);

                //when duplicating, the dataStatus must always be add, to force an insertion in the database
                if ( MIOLO::_REQUEST('duplicar') )
                {
                    $data[0]->dataStatus = self::STATUS_ADD;
                }

                $itensData[] = $data[0];

                self::setSessionValue('contentData', $itensData, $item);
            }
        }
    }

    /**
     * Define the Data of the field.
     * It will clearData e add the passed data
     *
     * @param (array) the array of objects with all data
     */
    public static function setData($data, $itemForce)
    {
        if ( !$data )
        {
            return;
        }

        $data = self::parseData($data, $itemForce);
        $controls = self::getFields($itemForce);

        if ( self::$doLookupAutocomplete )
        {
            // Trigger the autocomplete of lookups present in the subdetail
            $data = self::lookupAutocomplete($data, $controls);
        }

        self::clearData($itemForce);
        self::addData($data, $itemForce);
        self::getData($itemForce, false); // called to reorder the indices
    }

    /**
     * Static function that executes the autocomplete of lookups present in the passed array ($controls)
     *
     * @param array $data
     * @param array $controls
     */
    public static function lookupAutocomplete($data, $controls)
    {
        foreach ( $controls as $line => $control )
        {
            if ( $control->class != 'mlookuptextfield' )
            {
                continue;
            }

            $lookup = new MLookupContainer($control->name, $control->value, $control->label, $control->module, $control->item);
            $lookup->setContext($control->module, $control->item, $control->related, $control->filter, $control->autoComplete);

            $relatedArray = explode(',', $control->related);

            foreach ( $data as $key => $line )
            {
                if ( !$line->{$control->name} )
                {
                    continue;
                }

                $extraData = $lookup->doAutoComplete($line->{$control->name});

                // fetch lookup data for each row of the subdetail
                foreach ( $relatedArray as $rKey => $related )
                {
                    if ( !$data[$key]->$related )
                    {
                        $data[$key]->$related = $extraData[$rKey];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * clearData all itens of the table session
     */
    public static function clearData($itemForce)
    {
        self::setSessionValue('contentData', null, $itemForce);
    }

    /**
     *  Remove Data some item from Table, you need an Id.
     *  This id can be found is $item->arrayItem
     */
    public static function removeData($arrayItem, $item)
    {
        $itensData = self::getSessionValue('contentData', $item);
        unset($itensData[$arrayItem]);
        self::setSessionValue('contentData', $itensData, $item);
    }

    public static function ajaxHandler()
    {
        $MIOLO = MIOLO::getInstance();
        $args = MUtil::getAjaxActionArgs();
        $event = MIOLO::_REQUEST("{$MIOLO->page->getFormId()}__EVENTTARGETVALUE");

        //list of possible events
        $possibleEvents = array( 'removeFromTable', 'duplicate', 'addToTable', 'forceAddToTable', 'upFromTable', 'downFromTable', 'editFromTable', 'clearTableFields', 'editCell', 'editCellExit', 'removeFromTable' );

        //call the specific function
        if ( in_array($event, $possibleEvents) )
        {
            self::$event($args);
        }

        //"order" has a different pattern
        if ( $event == 'order' )
        {
            self::$event($args->mSubDetailOrderField, null, $args->mSubDetail);
        }
    }

    /**
     * Method to change the doLookupAutocomplete attribute
     * This attribute indicates whether the lookup autocomplete should be triggered when calling setValue
     *
     * Important: In setData, the attribute is not taken into consideration, because the method is static,
     * triggering the autocomplete of each lookup
     *
     * @param boolean $doLookupAutocomplete
     */
    public function setDoLookupAutocomplete($doLookupAutocomplete)
    {
        self::$doLookupAutocomplete = $doLookupAutocomplete;
    }

    public static function isFirstAccess($step = NULL)
    {
        $MIOLO = MIOLO::getInstance();

        if ( !$step )
        {
            $step = $this->step;
        }

        $stepsData = $this->getAllStepData();

        $data = $stepsData[$step];

        // if is set, returns true
        return !$data;
    }
    
    public static function setValidationError($errorMsg)
    {
        $MIOLO = MIOLO::getInstance();
        
        $errorMessage = MMessage::getStaticMessage('msgErrorValidation', $errorMsg, MMessage::TYPE_ERROR);
        $teste = (str_replace(PHP_EOL, '', $errorMessage->generate()));
        $MIOLO->page->addJsCode("document.getElementById('divErrorValidators').innerHTML = '{$teste}';");
    }
    
    public static function clearValidationErrors()
    {
        $MIOLO = MIOLO::getInstance();
        $MIOLO->page->addJsCode("document.getElementById('divErrorValidators').innerHTML='';");
    }
}

MSubDetail::ajaxHandler();

?>
