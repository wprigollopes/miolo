<?php
/**
 * Business class representing control example table
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/11/29
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
class BusinessExampleControl extends MBusiness
{
    /**
     * @var integer Id column
     */
    private $id;

    /**
     * @var string Form column
     */
    private $form;

    /**
     * @var string Field column
     */
    private $field;

    /**
     * @var string Value column
     */
    private $value;

    /**
     * Business constructor
     *
     * @param object $data Object of type stdClass to populate the instance
     */
    public function __construct($data=NULL)
    {
       parent::__construct('example');

       if ( $data )
       {
           $this->id = $data->id;
           $this->form = $data->formId;
           $this->field = $data->fieldId;
           $this->value = $data->fieldValue;
       }
    }

    /**
     * @param string $id Set the id.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string Get the id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $form Set the form.
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string Get the form.
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string $field Set the field.
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string Get the field.
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $value Set the value.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string Get the value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Insert the register on the table.
     *
     * @return boolean Whether the insert was successfull.
     */
    public function insert()
    {
        $this->id = $this->getDb()->getNewId('seq_miolo_control', 'cm_sequence');
        
        $msql = new MSQL('id, form, field, value', 'miolo_control');

        $sql = $msql->insert(array(
            $this->id,
            $this->form,
            $this->field,
            $this->value
        ));

        return $this->getDb()->execute($sql);
    }

    /**
     * Update the register on the table.
     *
     * @return boolean Whether the update was successfull.
     */
    public function update()
    {
        if ( !$this->field )
        {
            return false;
        }
        $msql = new MSQL('value', 'miolo_control');
        $msql->setWhere("form = ? AND field = ?");

        $sql = $msql->update(array(
            $this->value,
            $this->form,
            $this->field
        ));

        return $this->getDb()->execute($sql);
    }

    /**
     * Delete the register of the table.
     *
     * @return boolean Whether the delete was successfull.
     */
    public function delete()
    {
        if ( !$this->field )
        {
            return false;
        }
        $msql = new MSQL(NULL, 'miolo_control');
        $msql->setWhere("form = ? AND field = ?");

        $sql = $msql->delete(array(
            $this->form,
            $this->field
        ));

        return $this->getDb()->execute($sql);
    }

    /**
     * Search through the table.
     *
     * @param object $filters Object of type stdClass to filter the search.
     * @return array Search result on bidimensional array format.
     */
    public function search($columns='*', $filters=NULL)
    {
        $msql = new MSQL($columns, 'miolo_control');

        if ( $filters->form )
        {
            $msql->setWhere("form = ?");
            $query = $this->query($msql, $filters->form);
        }
        else
        {
            $query = $this->query($msql);
        }

        return $query->result;
    }
}
