<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Page Transfer', $module, $self);
    $form = $ui->GetForm($module,'frmPageTransfer');
    if ($page->IsPostBack())
    {
        $page->Transfer($module,'pagetransfer2');
    }
    else
    {
        $theme->InsertContent($form);
    }
?>
