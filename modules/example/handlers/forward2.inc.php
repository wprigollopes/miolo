<?
    if ($item == 'clear')
    {
        $theme->clearContent();
    } 
    $label = new MLabel("I'm at forward2 handler. The forward2 form below was appendded to the theme.");
    $theme->appendContent($label);
    $ui = $MIOLO->getUI();
    $navbar->addOption('Forward(2)', 'example', $self);
    $form = $ui->getForm('example','frmForward2');
    $theme->appendContent($form);
?>
