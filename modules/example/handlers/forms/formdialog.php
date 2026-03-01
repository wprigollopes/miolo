<?
    $ui = $MIOLO->getUI();
    switch($item)
    {
        case 'form1':
           $form = $ui->getForm($module,'frmFormDialog1');
           break;
        case 'form2':
           $form = $ui->getForm($module,'frmFormDialog2');
           break;
        case 'form3':
           $form = $ui->getForm($module,'frmFormDialog3');
           break;
        case 'form4':
           $form = $ui->getForm($module,'frmFormDialog4');
           break;
        default:
          $theme->clearContent();
          $navbar->addOption('Form Dialog', $module, $self);
          $form = $ui->getForm($module,'frmFormDialog');
    }
    $theme->setContent($form);
