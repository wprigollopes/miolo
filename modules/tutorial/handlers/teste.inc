<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Teste', 'tutorial', $self);
    $form = $ui->GetForm('tutorial','frmTeste');
    $theme->InsertContent($form);
?>
