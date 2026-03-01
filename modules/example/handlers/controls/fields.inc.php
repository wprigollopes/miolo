<?
    $ui = $MIOLO->getUI();

    $navbar->addOption('Fields Controls', $module, $self);
    $theme->breakContent();
    $panel = new Panel('pnlDummy', 'Fields Controls');
    $txtField = new TextField('txtField', 'value', 'Label', 15);
    $passField = new PasswordField('passField', 'value', 'Label', 15);
    $hidField = new HiddenField('hidField', 'value');
    $multiField = new MultilineField('multiField', 'value', 'Label', 25, 5, 20);
    $fileField = new FileField('fileField', 'value', 'Label', 30);
    $calField = new CalendarField('calField', '', 'Label');
    $currencyField = new CurrencyField('curField', '', 'Label');

    $controls = array
        (
        new Text('', "Text Field: "),
        $txtField,
        new Separator(),
        new Text('', "Password Field: "),
        $passField,
        new Separator(),
        new Text('', "Hidden Field: Uh! it's hidden!"),
        $hidField,
        new Separator(),
        new Text('', "MultiLineField: "),
        $multiField,
        new Separator(),
        new Text('', "File Field: "),
        $fileField,
        new Separator(),
        new Text('', "Calendar Field: "),
        $calField,
        new Separator(),
        new Text('', "Currency Field: "),
        $currencyField,
        new Separator()
        );

    $panel->addControl($controls);

    $theme->appendContent($panel);
