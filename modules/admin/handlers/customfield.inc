<?php

$perms->checkAccess('user', A_ACCESS, true);

$home = 'main:action';
$navbar->addOption(_M('Custom Fields', $module), $module, $home);

switch ( MIOLO::_REQUEST( 'function' ) )
{
    case 'insert':
    case 'edit':
        $form = $MIOLO->getUI()->getForm($module, 'frmCustomField');
        break;

    case 'search':
    default:
        $form = $MIOLO->getUI()->getForm($module, 'frmCustomFieldSearch');
}

$theme->appendContent($form);

?>