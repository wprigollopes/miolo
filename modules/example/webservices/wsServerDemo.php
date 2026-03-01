<?php

class wsServerDemo extends MWebService
{
    private $cities = array('95885-000' => 'Imigrante/RS',
                            '95880-000' => 'Estrela/RS',
                            '95900-000' => 'Lajeado/RS');

    // This method should be called by the client.
    public function getCity($postalCode)
    {
        if ( isset($this->cities[$postalCode]) )
        {
            return $this->cities[$postalCode];
        }
        else
        {
            throw new SoapFault('Server', "Postal code '$postalCode' was not found!");
        }
    }
}
