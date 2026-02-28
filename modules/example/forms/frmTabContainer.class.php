<?php

class frmTabContainer extends MForm
{
    public function __construct()
    {
        parent::__construct('TabContainer');
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        // Text for the example
        $lorem[0] = new MRawText('Lorem ipsum dolor sit amet, consectetur adipiscing elit, set eiusmod tempor incidunt et labore et dolore magna aliquam. Ut enim ad minim veniam, quis nostrud exerc. Irure dolor in reprehend incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse molestaie cillum. Tia non ob ea soluad incom dereud facilis est er expedit distinct.');
        $lorem[1] = new MRawText('Nam liber te conscient to factor tum poen legum odioque civiuda et tam. At vver eos et accusam dignissum qui blandit est praesent. Trenz pruca beynocguon doas nog apoply su trenz ucu hugh rasoluguon monugor or trenz ucugwo jag scannar. Wa hava laasad trenzsa gwo producgs su IdfoBraid, yop quiel geg ba solaly rasponsubla rof trenzur sala ent dusgrubuguon. Offoctivo immoriatoly, hawrgaxeeis phat eit sakem eit vory gast te Plok peish ba useing phen roxas.');
        $lorem[2] = new MRawText('Eslo idaffacgad gef trenz beynocguon quiel ba trenz Spraadshaag ent trenz dreek wirc procassidt program. Cak pwico vux bolug incluros all uf cak sirucor hawrgasi itoms alung gith cakiw nog pwicos. Lor sum amet, commy nulputat. Duipit lum ipisl eros dolortionsed tin hent aliquis illam volor in ea feum in ut adipsustrud elent ulluptat. Duisl ullan ex et am vulputem augiam doloreet amet enibh eui te dipit acillutat acilis amet, suscil er iuscilla con utat, quisis eu feugait ad dolore commy nullam iuscilisl iureril ilisl del ut pratuer iliquis acipissit accum quis nulluptat.');
        $lorem[3] = new MRawText('Dui bla faccumsan velis auguero con henis duismolor sumsandrem quat vulluptat alit er iniamcore exeriure vero core te dit ut nulla feummolore commod dipis augiamcommod tem ese dolestrud do odo odiamco eetummy nis aliquamcommy nonse eu feugue del eugiamconsed ming estrud magnis exero eumsandio enisim del dio od tat.sumsan et pratum velit ing etue te consequis alis nullan et, quis am iusci bla feummy.');

        // MTabContainer
        $fields['tab'] = new MTabContainer('panel');
        $fields['tab']->addPanel('part1', _M('Part @1', $module, '1'), $lorem[0]);
        $fields['tab']->addPanel('part2', _M('Part @1', $module, '2'), $lorem[1]);
        $fields['tab']->addPanel('part3', _M('Part @1', $module, '3'), $lorem[2]);
        $fields['tab']->addPanel('part4', _M('Part @1', $module, '4'), $lorem[3]);

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }
}
?>
