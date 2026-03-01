<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Datagrid2', $module, $self);
    $form = $ui->GetForm($module, 'frmDatagrid2');

    if ($theme->GetContent() == '')
    {
        $theme->InsertContent($form);
    }
?>
