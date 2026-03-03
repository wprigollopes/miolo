<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Datagrid2', $module, $self);
    $form = $ui->getForm($module, 'frmDatagrid2');

    if ($theme->getContent() == '')
    {
        $theme->insertContent($form);
    }
?>
