<?php

/**
 * MGrider example
 *
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * @since
 * Creation date 2011/03/21
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
class frmGrider extends MForm
{

    /**
     * Form constructor
     */
    public function __construct()
    {
        parent::__construct(_M('Grider', MIOLO::getCurrentModule()));
        $this->defaultButton = false;
        $this->eventHandler();
    }

    /**
     * Obtem grider de pedidos
     * 
     * @param array $data 
     */
    public function obterMyGrider($data = null)
    {
        $options[] = array( '1', 'Computador' );
        $options[] = array( '2', 'IPhone' );
        $options[] = array( '3', 'Mouse' );
        $options[] = array( '4', 'Teclado' );
        $options[] = array( '5', 'Screen' );
        $options[] = array( '6', 'Memória Usb' );
        $options[] = array( '7', 'Gravadora de CD' );
        $options[] = array( '8', 'Disco rígido' );
        $options[] = array( '9', 'Placa mãe' );
        $options[] = array( '10', 'Gabinete' );

        $controls[] = new MSelection('product', '', _M('Produto', 'example'), $options);
        $controls[] = new MFloatField('price', '', _M('Preço', 'example'));
        $controls[] = new MIntegerField('quantity', '', _M('Quantidade', 'example'));
        $controls[] = new MCalendarField('deliverDate', '', _M('Data de entrega', 'example'), true);
        $controls[] = new MCheckBox('discount', '', _M('Desconto', 'example'), true);

        $grider = new MGrider(_M('Itens do Pedido', 'example'), $controls, null, 'myGrider');
        $grider->setCountRow(true);
        $grider->setCountRowText('Nº do item');
        $grider->setWidth('100%');

        if ( $data )
        {
            $grider->setData($data);
        }

        return $grider;
    }

    /**
     * Create form fields
     */
    public function createFields()
    {
        $fields[] = MMessage::getMessageContainer();

        $fields[] = new MDiv('limbo', '</br>');

        //simula array de Types/Tipos
        $dataItem = new stdClass();
        $dataItem->product = 1;
        $dataItem->price = '2.00';
        $dataItem->quantity = '3';
        $dataItem->discount = true;

        $data[] = $dataItem;

        $dataItem = new stdClass();
        $dataItem->product = 2;
        $dataItem->price = '4.00';
        $dataItem->quantity = '5';
        $dataItem->discount = false;

        $data[] = $dataItem;

        $grider[] = new MDiv('myGriderContainer', $this->obterMyGrider($data));
        $grider[] = new MButton('btnAddItem', _M('Adicionar item via ajax', 'example'), ':addItem');
        $grider[] = new MButton('btnReadOnly', _M('Somente leitura'), ':readOnly');

        $fields[] = new MBaseGroup('myBaseGroup', _M('Pedido', 'example'), $grider);
        $fields[] = new MDiv('', '</br>');

        //segundo grider
        $colTitles[] = _M('Digite', 'example');
        $colTitles[] = _M('Escolha', 'example');
        $colTitles[] = _M('Data/hora', 'example');

        $fields[] = $grider2 = new MGrider('', null, $colTitles, 'secondGrider');
        $grider2->setAddAction("alert(\"Pressionado botão de adicionar.\")");
        $grider2->setDelAction("alert(\"Pressionado botão de remover.\")");

        $options[] = array( 't', 'Verdadeiro' );
        $options[] = array( 'f', 'Falso' );

        $controls[] = new MButton('btnRandomValue', _M('Sortear valor'), $grider2->getAjaxAction('randomValue'));
        $controls[] = new MTextField('digite');
        $controls[] = new MSelection('escolha', 'true', '', $options2, false, '', '', false);

        $grider2->setControls($controls);
        //$grider2->setAddRow(false);
        //$grider2->setDelRow(false);

        $fields[] = new MDiv('', '</br>');
        $fields[] = new MButton('myPost', _M('Enviar', 'example'), ':confirmation');
        $fields[] = new MDiv('', '</br>');

        $fields[] = MMessage::getStaticMessage('startMessage', '
            Grider : Componente simplificado para manutenção de dados repetitivos. </br>
            Componente experimental. </br>
            Não usa e nem irá usar sessão. </br>
            Não suporta Lookups.</br>
            Não suporta validadores.</br>
            Toda validação deve ser feita de forma manual.</br>
            Clique abaixo para visualizar o código fonte e ver o funcionamento componente.</br>
             ', MMessage::TYPE_INFORMATION);

        $fields[] = new ViewSource(__FILE__);

        $this->setFields($fields);
    }

    /**
     * Example of adding an item programmatically
     *
     * @param stdClass $args
     */
    public function addItem($args)
    {
        $data = $this->myGrider->getData();

        $dataItem = new stdClass();
        $dataItem->product = 4;
        $dataItem->price = '5.00';
        $dataItem->quantity = '6';
        $dataItem->discount = false;

        $data[] = $dataItem;

        $grider = $this->obterMyGrider($data);
        $this->setResponse($grider, 'myGriderContainer');
    }

    /**
     * Example of setting grider as read-only
     *
     * @param stdClass $args
     */
    public function readOnly($args)
    {
        $grider = $this->obterMyGrider($args->myGrider);
        $grider->setReadOnly(true);
        $this->setResponse($grider, 'myGriderContainer');
    }

    /**
     * Post confirmation
     *
     * @param string $args
     */
    public function confirmation($args)
    {
        $msg .= '1 - <pre>' . print_r($this->myGrider->getData(), 1) . '</pre>';
        $msg .= '2 - <pre>' . print_r($this->secondGrider->getData(), 1) . '</pre>';
        new MMessageInformation('Informações do Exemplo de Grider: </br>' . $msg);
    }

    /**
     * Coloca um valor sorteado no campo de texto da linha pressionada
     * @param string $args 
     */
    public function randomValue($args)
    {
        $pressedButton = mGrider::explodeName($args);

        $this->page->setElementValue($pressedButton->grider . '[' . $pressedButton->index . '][digite]', rand(0, 100));

        $this->setResponse('', 'limbo');
    }
}

?>