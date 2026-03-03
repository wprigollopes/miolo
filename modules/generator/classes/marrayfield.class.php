<?php

$MIOLO->page->addScript('m_arrayfield.js', MIOLO::getCurrentModule());

class MArrayField extends MContainer
{
    private $controlNames; //nome dos controles escaneados na função parseFields
    private $_name;
    private $_controls;

    public function __construct($name, $controls)
    {
        if ( !is_array($controls) )
        {
            $controls = array( $controls );
        }
        $MIOLO = MIOLO::getInstance();
        $this->_name = $name;
        $this->_controls = $controls;
        $controls = $this->parseFields($controls, $index);
        $container[] = $this->convertControl($controls); //joga os campos dentro de um container
        $container[] = new MHiddenField($name . '_counter', '0'); //adiciona contador
        parent::__construct($name, $container, 'vertical', MFormControl::FORM_MODE_SHOW_NBSP);
        $this->page->onload('marrayfield.parseLookup(\'' . $name . '\');');

        $this->setClass('mArrayField');
    }

    /**
     * Retorna um container com os campos
     *
     * @param unknown_type $controls
     * @param unknown_type $addButton
     * @return unknown
     */
    private function convertControl($controls, $addButton = true)
    {
        $MIOLO = MIOLO::getInstance();
        $addUrl = $MIOLO->getUI()->getImageTheme($MIOLO->getTheme()->getId(), 'add.png');
        $addImg = new MImageButton($this->_name . '_add', null, 'javascript:marrayfield.add(\'' . $this->_name . '\');', $addUrl);
        $delUrl = $MIOLO->getUI()->getImageTheme($MIOLO->getTheme()->getId(), 'remove.png');
        $delImg = new MImageButton($this->_name . '_remove', null, 'javascript:marrayfield.remove(this, \'' . $this->_name . '\');', $delUrl);

        if ( $addButton )
        {
            $delImg->addStyle('visibility', 'hidden'); //esconde del
        }
        else
        {
            $addImg->addStyle('visibility', 'hidden'); //esconde add
        }

        // Caso o primeiro e o último campo sejam containers
        if ( $controls[0] instanceof MContainer && $controls[count($controls) - 1] instanceof MContainer )
        {
            // Recria o botão de excluir para apagar um nível acima
            $delImg = new MImageButton($this->_name . '_remove', null, 'javascript:marrayfield.remove(this.parentNode, \'' . $this->_name . '\');', $delUrl);
            if ( $addButton )
            {
                $delImg->addStyle('visibility', 'hidden');
            }

            // Insere o botão adicionar na primeira posição dos controls
            $controls[0]->insertControl($addImg, 0);
            $controls[count($controls) - 1]->addControl($delImg);
        }
        // Caso o primeiro campo seja container
        elseif ( $controls[0] instanceof MContainer && !( $controls[count($controls) - 1] instanceof MContainer ) )
        {
            $controls[0]->insertControl($addImg, 0);
            $controls = array_merge(is_array($controls) ? $controls : array( $controls ), array( $delImg ));
        }
        // Caso o último campo seja container
        elseif ( $controls[count($controls) - 1] instanceof MContainer )
        {
            // Recria o botão de excluir para apagar um nível acima
            $delImg = new MImageButton($this->_name . '_remove', null, 'javascript:marrayfield.remove(this.parentNode, \'' . $this->_name . '\');', $delUrl);
            if ( $addButton )
            {
                $delImg->addStyle('visibility', 'hidden');
            }

            $controls = array_merge(array( $addImg ), is_array($controls) ? $controls : array( $controls ));
            $controls[count($controls) - 1]->addControl($delImg);
        }
        else
        {
            $controls = array_merge(array( $addImg ), is_array($controls) ? $controls : array( $controls ), array( $delImg ));
        }

        return new MContainer($this->_name . '_container', $controls, 'horizontal', MFormControl::FORM_MODE_SHOW_NBSP);
    }

    /**
     * Trabalha os campos internos do AArrayField,
     * registrando um array com o nome dos campos a tratar no get data,
     * assim como, tratando os id dos campos adicionando [] neles para que os dados sejam pegos corretamento no final 
     *
     * @param Object $field o campo ou array de campos
     * @param Object $data dados a serem forçados no campo
     * @return Object
     */
    private function parseFields($field, $data, $index = 0)
    {
        if ( is_array($field) )
        {
            foreach ( $field as $line => $info )
            {
                $field[$line] = $this->parseFields($info, $data, $index);
            }
            return $field;
        }
        else if ( $field instanceof ALookupField )
        {
            $newField = clone($field); //clona para pode aceitar vários

            $this->controlNames[] = $field->name;

            if ( $data )
            {
                $fieldName = str_replace('_container', '', $newField->name);
                $newField->setValue($data->$fieldName);
            }

            $controls = $newField->getControls();
            $child = clone( $controls[1] );

            if ( $child->name )
            {
                $child->name .='[' . $index . ']';
            }

            if ( $child->id )
            {
                $child->id .='[' . $index . ']';
            }

            $newField->setControl($child, 1);


            $controls = $newField->getControls();
            $childDesc = clone( $controls[2] );

            if ( $childDesc->name )
            {
                $childDesc->name .='[' . $index . ']';
            }

            if ( $childDesc->id )
            {
                $childDesc->id .='[' . $index . ']';
            }

            $newField->setControl($childDesc, 2);

            return $newField;
        }
        else if ( ($field instanceof MContainer ) )
        {
            $field->setControls($this->parseFields($field->getControls(), $data, $index));

            return $field;
        }
        else if ( ($field instanceof MDiv ) )
        {
            $field->setInner($this->parseFields($field->getInner(), $data, $index));

            return $field;
        }
        elseif ( $field->name )
        {
            $newField = clone($field); //clona para pode aceitar vários

            $this->controlNames[] = $field->name;

            if ( $newField->name )
            {
                $newField->name .='[' . $index . ']';
            }

            if ( $newField->id )
            {
                $newField->id .='[' . $index . ']';
            }

            if ( $data )
            {
                $fieldName = str_replace('[' . $index . ']', '', $newField->name);
                $newField->setValue($data->$fieldName);
            }

            return $newField;
        }
    }

    function setData($data)
    {
        $this->setControls(null);

        $addButton = true;

        if ( is_array($data) )
        {
            if ( is_object($data[0]) )
            {
                foreach ( $data as $line => $obj )
                {
                    $controls = $this->parseFields($this->_controls, $obj, $line);
                    $this->addControl($this->convertControl($controls, $addButton));
                    $addButton = false;
                    $index = $line;
                }
            }
            else
            {
                foreach ( $data as $line => $array ) //dados
                {
                    $i = 0;
                    $controls = $this->parseFields($this->_controls, null, $line);

                    if ( is_array($array) )
                    {
                        foreach ( $array as $l => $i )
                        {
                            if ( is_object($controls[$l]) )
                            {
                                if ( method_exists($controls[$l], 'setValue') )
                                {
                                    $controls[$l]->setValue($i);
                                }
                            }
                        }
                    }

                    $this->addControl($this->convertControl($controls, $addButton));
                    $addButton = false;
                    $index = $line;
                }
            }

            $this->addControl(new MHiddenField($this->name . '_counter', $index ? $index : '0'));
        }
        else
        {
            $controls = $this->parseFields($this->_controls, $obj);
            $this->addControl($this->convertControl($controls, $addButton));
            $this->addControl(new MHiddenField($this->name . '_counter', $index ? $index : '0'));
        }
    }

    /**
     * Retorna um array de objetos, onde cada linha é um registro do ArrayField
     *
     * @return Array de objetos
     */
    function getData()
    {
        $data = (Object) $_REQUEST;
        $controlData = null;

        //separa só os dados necessários
        if ( is_array($this->controlNames) )
        {
            foreach ( $this->controlNames as $line => $info )
            {
                $controlData[$info] = $data->$info;
            }
        }

        //retorna no formato adequado
        if ( is_array($controlData) )
        {
            foreach ( $controlData as $indice => $dado )
            {
                if ( is_array($dado) )
                {
                    foreach ( $dado as $line => $info )
                    {
                        $resultData[$line]->$indice = $info;
                    }
                }
            }
        }

        return $resultData;
    }
}

?>