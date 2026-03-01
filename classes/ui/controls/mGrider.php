<?php

/**
 * MSubDetail class
 *
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * @since
 * Creation date 2012/09/11
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 * 
 * Based on http://boriscy.github.com/grider/#table1
 * 
 */
$MIOLO = MIOLO::getInstance();
$MIOLO->page->addScript('m_grider.js');

class MGrider extends MTableRaw
{
    /**
     * Controls used by the component
     * @var array
     */
    protected $controls;

    /**
     * Data used by the component
     * @var array
     */
    protected $data;

    /**
     * Additional JavaScript for the add item button
     * @var string
     */
    protected $addAction;

    /**
     * Additional JavaScript for the remove item button
     *
     * @var string
     */
    protected $delAction;

    /**
     * Defines whether to show row count or not
     *
     * @var boolean
     */
    protected $countRow;

    /**
     * Text to appear in the row count column
     * @var string
     */
    protected $countRowText;

    /**
     * Whether to show the add button
     * @var boolean
     */
    protected $addRow = true;

    /**
     * Whether to show the remove button
     * @var boolean
     */
    protected $delRow = true;
    
    
    static $i = 1;

    const STATUS_ADD = 'add';
    const STATUS_EDIT = 'edit';
    const STATUS_REMOVE = 'remove';

    public function __construct($title = '', $controls=NULL, $colTitle = NULL, $name = '', $data = null)
    {
        if ( !$name )
        {
            throw new Exception(_M('Name not informed in new MGrider.', 'miolo'));
        }

        parent::__construct($title, null, $colTitle, $name);

        //default configurations
        $this->setCountRow(false);
        $this->setAlternate(false);
        $this->setCountRowText('Nº');
        $this->setClass("mGrider");
        
        // Remove the table header columns
        foreach($controls as $key => $control)
        {
            $this->setHeadClass($key, 'mGriderHead');
        }
        // Remove the 'Delete' header column
        $this->setHeadClass(count($controls), 'mGriderHead');
        
        $this->setControls($controls);

        if ( $data )
        {
            $this->setData($data);
        }
    }

    /**
     * Defines the initial controls of the component
     *
     * @param array $controls
     */
    public function setControls($controls)
    {
        $this->controls = $controls;
    }

    /**
     * Gets the current controls of the component
     *
     * @return array
     */
    public function getControls()
    {
        return $this->controls;
    }

    /**
     * Defines whether to show row count or not
     *
     * @param boolean $countRow
     */
    public function setCountRow($countRow)
    {
        $this->countRow = $countRow;
    }

    /**
     * Returns whether to show row count or not
     *
     * @param boolean $countRow
     */
    public function getCountRow()
    {
        return $this->countRow;
    }

    /**
     * Defines the content of the add item button
     *
     * @param string $rowText
     */
    public function setAddAction($javascript)
    {
        $this->addAction = $javascript;
    }

    /**
     * Gets the content of the add item button
     *
     * @return string
     */
    public function getAddAction()
    {
        return $this->addAction;
    }

    /**
     * Defines the content of the remove item button
     *
     * @param string $rowText
     */
    public function setDelAction($javascript)
    {
        $this->delAction = $javascript;
    }

    /**
     * Gets the content of the remove item button
     *
     * @return string
     */
    public function getDelAction()
    {
        return $this->delAction;
    }

    /**
     * Text to appear in the count column
     *
     * @param string $countRowText
     */
    public function setCountRowText($countRowText)
    {
        $this->countRowText = $countRowText;
    }

    /**
     * Gets the text to appear in the count column
     *
     * @return string $countRowText
     */
    public function getCountRowText($countRowText)
    {
        return $this->countRowText;
    }

    /**
     * Show/hide the add button
     *
     * @param type $addRow
     */
    public function setAddRow($addRow)
    {
        $this->addRow = $addRow;
    }

    /**
     * Whether the add button is enabled
     *
     * @return boolean
     */
    public function getAddRow()
    {
        return $this->addRow;
    }

    /**
     * Show/hide the remove button
     * @param boolean $delRow
     */
    public function setDelRow($delRow)
    {
        $this->delRow = $delRow;
    }

    /**
     * Returns whether the remove button is enabled
     *
     * @return boolean
     */
    public function getDelRow()
    {
        return $this->delRow;
    }

    public function generate()
    {
        $controls = $this->controls;

        if ( !$controls )
        {
            throw new Exception(_M("É necessário definir algum controle para o ideal funcionamento do mGrider."));
        }

        //if there is no data, create a simplified one to work correctly
        if ( !is_array($this->data) || count($this->data) == 0 )
        {
            $this->data[] = new stdClass();
        }

        //if there are no columns, create default ones based on the field labels
        if ( !$this->colTitle )
        {
            foreach ( $controls as $line => $control )
            {
                $this->colTitle[] = $control->label ? $control->label : $control->name;
            }
        }

        //iterates through the data building initial fields
        if ( is_array($controls) )
        {
            $controls[] = $status = new MTextField('status', 'original');
            $status->setClass('griderStatus');

            //store the original names
            foreach ( $controls as $line => $control )
            {
                $originalName[] = $control->name;
            }

            $tmpControls = $controls;
            $controls = null;

            //this is necessary, otherwise tableRaw cannot handle the data correctly
            $this->data = array_values($this->data);

            foreach ( $this->data as $line => $dataItem )
            {
                //hide column of
                $this->setCellAttribute($line, count($this->colTitle), 'style', 'display:none;');
                $controls[$line] = $tmpControls;

                //determines whether or not to hide the row
                if ( is_array($dataItem) && $dataItem['status'] == self::STATUS_REMOVE
                        || ( is_object($dataItem) && $dataItem->status == self::STATUS_REMOVE ) )
                {
                    $this->setRowAttribute($line, 'style', 'display:none;');
                }

                foreach ( $tmpControls as $item => $control )
                {
                    if ($tmpControls[$item] instanceOf bChoice )
                    {
                        $innerControls = $tmpControls[$item]->getControls();
                        $innerControls[0]->id = $innerControls[0]->id.'['.$line.']';
                        $innerControls[0]->name = $innerControls[0]->name.'['.$line.']';
                        $innerControls[1]->id = $innerControls[1]->id.'['.$line.']';
                        $innerControls[1]->name = $innerControls[1]->name.'['.$line.']';
                    }
                    
                    //clone control to make value assignment work
                    $controls[$line][$item] = clone $tmpControls[$item];
                    $myOriginalName = $originalName[$item];

                    $controls[$line][$item]->name = $this->name . '[' . $line . '][' . $myOriginalName . ']'; //fix name
                    $controls[$line][$item]->id = $this->name . '[' . $line . '][' . $myOriginalName . ']'; //fix id
                    
                    $value = '';
                    
                    //if it is an object, Type case
                    if ( ($myOriginalName != null) && (is_object($this->data[$line])) )
                    {
                        $value = $this->data[$line]->$myOriginalName;
                    }
                    else if ( is_array($this->data[$line]) ) //if it is an array, post case
                    {
                        $value = $this->data[$line][$myOriginalName];
                    }

                    //add checkbox support
                    if ( $controls[$line][$item] instanceof MCheckBox )
                    {
                        $checked = isset($value);
                        $controls[$line][$item]->checked = $checked;
                    }
                    else
                    {
                        $controls[$line][$item]->value = $value; //set value
                    }

                    //set internal fields as read-only if the grider is set as such
                    if ( $this->readonly == true && method_exists($control, 'setReadOnly') )
                    {
                        $controls[$line][$item]->setReadOnly(true);
                    }
                }
            }

            $this->array = $controls;
        }

        //if read-only, it becomes a normal table
        if ( $this->readonly == false )
        {
            if ( $this->delRow )
            {
                $this->colTitle[] = _M('Delete', 'miolo');
            }

            $addImg = $this->manager->getUI()->getImage(NULL, 'button_add.png');
            $delImg = $this->manager->getUI()->getImage(NULL, 'button_cancel.png');
            $showRowCount = $this->countRow == true ? 'true' : 'false';
            $showDelRow = $this->delRow == true ? 'true' : 'false';
            $showAddRow = $this->addRow == true ? 'true' : 'false';

            /**
             * Note:
             * The code below passes the information to the javascript component.
             * Unfortunately, I could not use miolo to generate the buttons; it caused a javascript error.
             */
            //mount grider if it has not been mounted yet
            $this->manager->page->onload("if ( !$('#addRow{$this->name}')[0] )
            {
                var {$this->name}Mount = $('#{$this->name}').grider(
                    {
                        countRow: $showRowCount,
                        countRowAdd: true,
                        countRowText: '{$this->countRowText}',
                        addRow: {$showAddRow},
                        delRow: {$showDelRow},
                        addRowText: '<a id=\'addRow{$this->name}\' class=\'addButton\' onclick=\'{$this->addAction}; return false;\'><button class=\'mButton\'><img src=\'$addImg\'/>&nbspAdicionar</button></a>',
                        delRowText: '<td><a onclick=\'{$this->delAction} ; return false;\' class=\'delete\'><button class=\'mGriderButtonExcluir\'><img src=\'$delImg\'/></button></a></td>',
                    }
                )
            };");
        }

        //TODO sum, average, formula,

        return parent::generate();
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData($class = 'stdClass')
    {
        $data = MIOLO::_REQUEST($this->name);

        if ( $class && $data )
        {
            foreach ( $data as $line => $itemData )
            {
                $infoObject = new $class;

                foreach ( $itemData as $item => $info )
                {
                    $infoObject->$item = $info;
                }

                $data[$line] = $infoObject;
            }
        }

        return $data;
    }

    /**
     * Returns an ajax action prepared for the mGrider
     *
     * @param string $event ajax event to be called
     * @return string
     */
    public function getAjaxAction($event)
    {
        return "miolo.doAjax('$event',this.id,'{$this->manager->page->getFormId()}');";
    }

    /**
     * Splits a composite mGrider id/name into parts
     *
     * @param string $args
     * @return stdClass
     */
    public static function explodeName($args)
    {
        $explode = explode('[', $args);

        $name = new stdClass();
        $name->grider = $explode[0];
        $name->index = str_replace(']', '', $explode[1]);
        $name->field = str_replace(']', '', $explode[2]);

        return $name;
    }
}
