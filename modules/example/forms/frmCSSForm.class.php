<?php

class frmCSSForm extends MCSSForm
{
    public function __construct()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();

        $colors = array(
            'red' => _M('Red', $module),
            'blue' => _M('Blue', $module),
            'green' => _M('Green', $module)
        );
        parent::__construct(_M('Relative CSS Form Sample', $module));
//        $this->setTitle('Exemplo de Relative CSS Form');
//  SetField($row, $col, $width, $field, $style)
//      $row = nÃºmero da linha no formulÃ¡rio, comeÃ§ando em 0; as linhas sÃ£o renderizadas em ordem crescente
//      $col = nÃºmero da coluna (dentro da linha), comeÃ§ando em 0
//      $width = valor (em %) para a largura da coluna
//      $field = campo a ser registrado no formulÃ¡rio (com $form->addField)
//      $style = array: chave=atributo css, valor=valor do atributo; renderizado como style
//  HiddenFields sÃ£o adicionados diretamente com AddField
        $this->addField(new MHiddenField('hidden1', '1'));
        $this->addField(new MHiddenField('hidden2', '2'));
        $this->addField(new MHiddenField('hidden3', '3'));

// Campos
// Texto simples
        $this->setField(0, 0, 15, new MText("", "[0,0]15"));
        $this->setField(0, 1, 85, new MText("", "[0,1]85"));
        $this->setField(1, 0, 15, new MText("", "[1,0]15"));
// Styles
        $this->setField(1, 1, 15, new MText("", "[1,1]15"), array( "text-align" => "right", "background-color" => "#999999" ));
        $this->setField(1, 2, 70, new MText("", "[1,2]70"), array( "background-color" => "#0000FF" ));
        $this->setField(2, 0, 100, new MText("", "[2,0]100"));
// Inputs
        $this->setField(3, 0, 25, new MText("", _M('Input Field', $module)), array( "text-align" => 'right' ));
        $this->setField(3, 1, 30, new MTextField("edtText", '', '', 25));
        $this->setField(3, 2, 45, new MSelection("selColor", '', '', $colors));
// Simples
        $this->setField(4, 0, 50, new MText("", "[4,1]50"));
        $this->setField(4, 1, 25, new MText("", "[4,1]25"));
        $this->setField(4, 2, 25, new MText("", "[4,2]20"));
// Uma linha em branco
        $this->setField(5, 0, 100, new MLabel("&nbsp;"));
// Outra uma linha...
        $this->setField(6, 0, 100, new MText("", "[6,0]100"));
// Mais de um controle 
        $fields = array(
            new MTextField('edtField1', 'array', '', 15),
            new MTextField('edtField2', _M('of fields', $module), '', 15),
            new MSelection("selColor2", '', '', $colors)
        );
        $this->setField(7, 0, 60, $fields);
// Input
        $this->setField(8, 0, 100, new MCheckBox('chkBox', 'value', _M('Label', $module), true, _M('Alternate Colors', $module)));
        $buttons = array(
            new MButton('btnPost', _M('Send')),
        );
        $this->setButtons($buttons);
        $this->setAlternate(true);
        if ( $page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        $texto = _M('Input Field', $module);
        $texto .= $this->getFieldValue('edtText') . ' (';
        $texto .= _M('this color was set at above line', $module) . '...)';
        $cor = $this->getFieldValue('selColor');
        $cor2 = $this->getFieldValue('selColor2');
// acrescentando dados a uma linha existente
        $this->setField(7, 1, 40, new MText("", $cor2));
// colocando dados na linha em branco
        $this->setField(5, 0, 100, new MText("", $texto), array( "color" => $cor ));
        if ( $this->chkBox->checked )
        {
// SetAlternate: formulario "zebrado"
            $this->setAlternate(true);
        }
    }
}
?>
