
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('%TABLE_CLASS_NAME%', $module), $module, $action);

switch ( MIOLO::_REQUEST( 'function' ) )
{
    case 'insert':
    case 'edit':
        $form = $ui->getForm($module, 'frm%TABLE_CLASS_NAME%');
        break;

    case 'search':
    default:
        $form = $ui->getForm($module, 'frmBusca%TABLE_CLASS_NAME%');
}

$theme->insertContent($form);
