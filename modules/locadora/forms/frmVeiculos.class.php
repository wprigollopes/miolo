<?php

/**
 *
 */
class FrmVeiculos extends MForm
{
    /**
     *
     */
    public function __construct()
    {
        // construtor da classe
        parent::__construct('Cadastro de VeÃ­culos');

        // define array of buttons
        $buttons = Array( new FormButton( 'btnPost', _M('Gravar') ),
                          new FormButton( 'btnListar', _M('Listar VeÃ­culos') )
                         );

        // add the buttons to the form
        $this->setButtons( $buttons );

        // call the eventHandler
        $this->eventHandler();

    }

    /**
     *
     */
    public function createFields()
    {
        $this->addField( new MTextField( 'placa', Form::getFormValue('placa'), 'Placa', 10 , 'Informe a placa do veÃ­culo' ) );
        $this->placa->setJsHint( 'Informe a placa do veÃ­culo' );
        $this->page->onLoad('document.'.$this->name.'.placa.focus();');

        $this->addField( new MTextField( 'modelo', Form::getFormValue('modelo'), 'Modelo/Marca',  40, 'Informe o modelo/marca do veÃ­culo') );
        $this->modelo->setJsHint( 'Informe o modelo/marca do veÃ­culo' );
    }

    /**
     * Default post event handler
     */
    public function btnPost_click( $sender = NULL )
    {
		$module = MIOLO::getCurrentModule();
		$action = MIOLO::getCurrentAction();

        $data = $this->getData();

        //$MIOLO = $this->manager;
        $MIOLO = MIOLO::getInstance();

        $business = $MIOLO->getBusiness( 'locadora', 'veiculos' );
        $business->setData( $data );

        if ($business->updateVehicle() )
        {
            $msg  = 'Dados gravados.';
            $goto = $MIOLO->getActionURL( $module, $action );
            $caption = 'InformaÃ§Ã£o';
            $MIOLO->information( $msg, $goto );
        }
        else
        {
            $msg  = 'NÃ£o foi possÃ­vel Gravar os dados.';
            $goto = $MIOLO->getActionURL( $module, $action );
            $caption = 'ERRO';
            $MIOLO->error( $msg, $goto, $caption );
        }
    }

    /**
     * This is the default handler for the button btnListar
     */
    public function btnListar_click( $sender = NULL )
    {
        $text = new MText( 'eventText', 'Implementar: listar... criar grid,...' );
        $sender->color = $text->color = 'red';
        $this->addField( $text );
    }

    /**
     *
     */
    public function getData()
    {
        if ( $this->validateAll() )
        {
            return $this->collectInput( new FormData() );
        }

        return false;
    }


}

?>
