<?
// clear the page content
    $theme->clearContent();

// get access to User Interface classes
    $ui = $MIOLO->getUI();

// adding link to the navigation bar
    $navbar->addOption('State', $module, $self);

// create the form, defined in the file example/forms/FrmSession.class
    $formState = $ui->getForm($module,'frmState');

// place the page content (first the menubar, then the $page)
if ($theme->getContent()  == '')
{
    $theme->insertContent($formState);
}

?>
