<?
    $theme->clearContent();
    $label = new MLabel("I'm at forward1 handler. The content of theme was cleared. The forward1 form below was appendded to the theme.");
    $theme->insertContent($label);
    $ui = $MIOLO->getUI();
    $navbar->addOption('Forward(1)', 'example', $self);
    $form = $ui->getForm('example','frmForward1');
    $theme->appendContent($form);
