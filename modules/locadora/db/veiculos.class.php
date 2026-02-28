<?php

/**
 *
 */
class BusinessLocadoraVeiculos extends Business
{
    private $placa;
    private $modelo;

    /**
     * Override of the GetDatabase method, to simplify the call
     * to GetDatabase
     */
    public function getDatabase()
    {
        $MIOLO = MIOLO::getInstance();

        // get connection to the 'locadora' database
        // the 'locadora' database is defined in the locadora.conf file
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
        // sql for inserting the vehicle.
        $sql = "insert into lcd_veiculos " .
               "       ( placa, modelo ) " .
               "values " .
               "       ( ?, ? ) ";

        $args = array( $objVehicle->placa,
                       $objVehicle->modelo
                      );

        // execute the $sql statement and return true or false
        $rs = $db->query( $db->prepare( $sql, $obj ) );

        $this->checkError( $db );
    }

    /**
     *
     */
    public function getVehicles()
    {
        // sql query
        $sql = " select placa, textcat(textcat(placa,' '),modelo)" .
               "   from lcd_veiculos ".
               "  order by modelo";

        // open connection to the 'locadora' database
        $db = $this->getDatabase( 'locadora' );

        // execute the $sql query
        $data = $db->query( $sql );

        // check for query errors and add to the object
        $this->checkError( $db );

        // return the vehicles
        return $data;
    }

    /**
     * @param $placa (string) Vehicle license plate
     */
    public function getVehicle( $placa )
    {
        // sql query
        $sql = " select placa, modelo".
               "   from lcd_veiculos ".
               "  where placa = ?";

        // open connection to the 'locadora' database
        $db = $this->getDatabase();

        // execute the $sql query
        $data = $db->query( $db->prepare( $sql, $placa ) );

        // if the query returned data,
        // set in the attributes
        if ( $data )
        {
            $this->setData( $data );
        }

        // check for query errors and add to the object
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
