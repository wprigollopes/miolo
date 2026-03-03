<?php

/**
 *
 */
class BusinessLocadoraVeiculos extends Business
{
    private $placa;
    private $modelo;

    /**
     * Override do mÃ©todo GetDatabase, para facilitar a chamada 
     * ao GetDatabase
     */
    public function getDatabase()
    {
        $MIOLO = MIOLO::getInstance();

        // obter conexÃ£o com a base 'locadora'
        // a base 'locadora' Ã© definida no arquivo locadora.conf
        return $MIOLO->getDatabase( 'locadora' );
    }

    /**
     * @param $data (array) Database query result
     */
    public function setData( $data = null )
    {
        $this->placa  = $data[0][0];
        $this->modelo = $data[0][1];
    }

    /**
     *
     */
     public function insertVehicle( $objVehicle )
    {
        // sql para inclusÃ£o do veiculo.
        $sql = "insert into lcd_veiculos " .
               "       ( placa, modelo ) " .
               "values " .
               "       ( ?, ? ) ";

        $args = array( $objVehicle->placa,
                       $objVehicle->modelo
                      );

        // executa a instruÃ§Ã£o $sql e retorna true ou false
        $rs = $db->query( $db->prepare( $sql, $obj ) );

        $this->checkError( $db );
    }

    /**
     *
     */
    public function getVehicles()
    {
        // consulta sql
        $sql = " select placa, textcat(textcat(placa,' '),modelo)" .
               "   from lcd_veiculos ".
               "  order by modelo";

        // abre conexÃ£o com a base 'locadora'
        $db = $this->getDatabase( 'locadora' );

        // executa a consulta $sql
        $data = $db->query( $sql );

        // verifica erros na query e adiciona ao objeto
        $this->checkError( $db );

        // retorna os veÃ­culos
        return $data;
    }

    /**
     * @param $placa (string) Placa do veÃ­culo
     */
    public function getVehicle( $placa )
    {
        // consulta sql
        $sql = " select placa, modelo".
               "   from lcd_veiculos ".
               "  where placa = ?";

        // abre conexÃ£o com a base 'locadora'
        $db = $this->getDatabase();

        // executa a consulta $sql
        $data = $db->query( $db->prepare( $sql, $placa ) );

        // se a consulta retornou dados, 
        // setar nos atributos
        if ( $data )
        {
            $this->setData( $data );
        }

        // verifica erros na query e adiciona ao objeto
        $this->checkError( $db );
    }

    /**
     *
     */
    public function updateVehicle()
    {
        $sql = "update lcd_veiculos ".
                "   set modelo = ?" .
                " where placa  = ?";

        $args = array( $this->modelo,
                       $this->placa
                      );
        $ok = $db->execute( $db->prepare($sql, $args) );

        $this->checkError( $db );

        return $ok;
    }

}

?>
