<?php

class frmComplexCSS extends MCSSForm
{
    public function __construct()
    {
        parent::__construct(_M('Complex Layout using CSS', MIOLO::getCurrentModule()));
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
        $lblMedida = new MLabel(_M('Measures in', $module));
        $lblMedida->width = '100%';
        $lblMedida->box->textAlign = 'right';

        $regua = new MSelection('regua', '', '', $medidas);
        $lblRegua = new MLabel(_M('Vertical rule in', $module));
        $lblRegua->width = '100%';
        $lblRegua->box->textAlign = 'right';

        $pontos = new MTextField('pontos', '', '', 3);
        $lblPontos = new MLabel(_M('points', $module));

        $lblFalhas = new MLabel(_M('Layout breaks', $module));
        $lblFalhas->width = '100%';
        $lblFalhas->box->textAlign = 'right';

        $chk1 = new MCheckBox('chk1', 'c1', '', false, _M('Display near lines', $module));
        $chk2 = new MCheckBox('chk2', 'c2', '', false, _M('Display paragraphs trespassing', $module));

        $grpFalhas = new MCheckBoxGroup('grpFalhas', '', array( $chk1, $chk2 ), '', 'vertical', 'none');

        $lblGraficos = new MLabel(_M('Charts display', $module));
        $lblGraficos->width = '100%';
        $lblGraficos->box->textAlign = 'right';

        $rb1 = array(
            new MOption('opt1', 'o1', _M('Simulated', $module), false),
            new MOption('opt2', 'o2', _M('Default', $module), false),
            new MOption('opt3', 'o3', _M('High resolution', $module), false),
        );

        $grpGraficos = new MRadioButtonGroup('grpGraficos', '', $rb1, 'o2');

        $btnOk = new MButton('btnOk', 'OK');
        $btnOk->width = '90px';
        $btnCancel = new MButton('btnCancel', _M('Cancel', $module));
        $btnCancel->width = '90px';

        $spacer = new MSpacer('5px');

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

        $this->setField(0, 0, 25, $lblMedida);
        $this->setField(0, 1, 55, $medida);
        $this->setField(0, 2, 20, $btnOk);
        $this->setField(1, 0, 25, $lblRegua);
        $this->setField(1, 1, 55, $regua);
        $this->setField(1, 2, 20, $btnCancel);
        $this->setField(2, 0, 25, $lblFalhas);
        $this->setField(2, 1, 75, $grpFalhas);
        $this->setField(3, 0, 25, $lblGraficos);
        $this->setField(3, 1, 75, $grpGraficos);
        $this->setField(4, 0, 55, $painel);
        $this->setField(4, 1, 40, $salvar);

        $this->defaultButton = false;
    }
}
?>
