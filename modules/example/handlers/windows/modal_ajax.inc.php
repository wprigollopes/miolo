<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Modal_Ajax', $module, $self);
    switch($item)
    {
        case 'form1':
           $form = $ui->getForm($module,'frmWinCurso','','windows');
           break;
        case 'form2':
           $form = $ui->getForm($module,'frmWinSala','','windows');
           break;
        default:
           $theme->clearContent();
           $form = $ui->getForm($module,'frmWinMatricula','','windows');
    }
    $theme->setContent($form);
?>