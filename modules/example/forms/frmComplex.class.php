<?php

class frmComplex extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Complex Layout using Containers', $module));
        $this->setWidth('500px');
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $medidas = array(
            _M('Milimeters', $module),
            _M('Inches', $module),
            _M('Points', $module)
        );

        $medida = new MSelection('medida', '', '', $medidas);
        $lblMedida = new MLabel(_M('Measures in', $module) . ':');
        $lblMedida->width = '100px';
        $lblMedida->box->textAlign = 'right';

        $regua = new MSelection('regua', '', '', $medidas);
        $lblRegua = new MLabel(_M('Vertical rule in', $module) . ':');
        $lblRegua->width = '100px';
        $lblRegua->box->textAlign = 'right';

        $pontos = new MTextField('pontos', '', '', 3);
        $lblPontos = new MLabel(_M('points', $module));

        $lblFalhas = new MLabel(_M('Layout breaks', $module));
        $lblFalhas->width = '100px';
        $lblFalhas->box->textAlign = 'right';

        $chk1 = new MCheckBox('chk1', 'c1', '', false, _M('Display near lines', $module));
        $chk2 = new MCheckBox('chk2', 'c2', '', false, _M('Display paragraphs trespassing', $module));

        $grpFalhas = new MCheckBoxGroup('grpFalhas', '', array( $chk1, $chk2 ), '', 'vertical', 'none');
        $grpFalhas->width = '200px';

        $lblGraficos = new MLabel(_M('Charts display', $module));
        $lblGraficos->width = '100px';
        $lblGraficos->box->textAlign = 'right';

        $rb1 = array(
            new MOption('opt1', 'o1', _M('Simulated', $module), false),
            new MOption('opt2', 'o2', _M('Default', $module), false),
            new MOption('opt3', 'o3', _M('High resolution', $module), false),
        );

        $grpGraficos = new MRadioButtonGroup('grpGraficos', '', $rb1, 'o2');
        $grpGraficos->width = '200px';


        $cMedida = new MHContainer('', array( $lblMedida, $medida ));
        $cRegua = new MHContainer('', array( $lblRegua, $regua, $pontos, $lblPontos ));
        $cFalhas = new MHContainer('', array( $lblFalhas, $grpFalhas ));
        $cGraficos = new MHContainer('', array( $lblGraficos, $grpGraficos ));

        $btnOk = new MButton('btnOk', 'OK');
        $btnOk->width = '90px';
        $btnCancel = new MButton('btnCancel', _M('Cancel', $module));
        $btnCancel->width = '90px';

        $spacer = new MSpacer('5px');

        $h1v1 = new MVContainer('', array( $cMedida, $spacer, $cRegua, $spacer, $cFalhas, $spacer, $cGraficos ));
        $h1v1->width = '380px';
        $h1v2 = new MVContainer('', array( $btnOk, $spacer, $btnCancel ));
        $h1v2->width = '100px';

        $h1 = new MHContainer('', array( $h1v1, $h1v2 ));

        $lblAjusteH = new MLabel(_M('Horizontal adjustment', $module));
        $lblAjusteH->width = '100px';
        $lblAjusteH->box->textAlign = 'right';
        $txtAjusteH = new MTextField('txtAjusteH', '1', '', 3);
        $selAjusteH = new MSelection('selAjusteH', '', '', $medidas);

        $cAjusteH = new MHContainer('', array( $lblAjusteH, $txtAjusteH, $selAjusteH ));

        $lblAjusteV = new MLabel(_M('Vertical adjustment', $module));
        $lblAjusteV->width = '100px';
        $lblAjusteV->box->textAlign = 'right';
        $txtAjusteV = new MTextField('txtAjusteV', '1', '', 3);
        $selAjusteV = new MSelection('selAjusteV', '', '', $medidas);

        $cAjusteV = new MHContainer('', array( $lblAjusteV, $txtAjusteV, $selAjusteV ));

        $chk3 = new MCheckBox('chk3', 'c3', '', false, _M('Accede to limits', $module));

        $painel = new MBaseGroup('painel', _M('Control Panel', $module), array( $spacer, $cAjusteH, $cAjusteV, $chk3 ), 'vertical', 'css');
        $painel->width = '250px';

        $rb2 = array(
            new MOption('opt1', 'o1', _M('Faster', $module), false),
            new MOption('opt2', 'o2', _M('Smaller', $module), false)
        );

        $salvar = new MRadioButtonGroup('rbtSalvar', _M('Saving options', $module), $rb2, false, '', 'vertical', 'css');
        $salvar->width = '120px';

        $h2 = new MHContainer('', array( $painel, $salvar ));

        $fields = array( $h1, $h2 );

        $this->setFields($fields);
        $this->defaultButton = false;
    }
}
?>
