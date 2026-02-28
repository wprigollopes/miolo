<?
	/**
	 * Clear the page content
	 */
    $theme->clearContent();

	/**
	 * Get access to User Interface classes
	 */
    $ui = $MIOLO->getUI();

	/**
	 * Adding link to the navigation bar
	 */
    $navbar->addOption('Save', $module, $self);

	/**
	 * Create the form, defined in the file example/forms/FrmSession.class
	 */
    $formSave = $ui->getForm($module,'frmSave');

	/**
	 * Place the page content (first the menubar, then the $page)
	 */
	if ($theme->getContent()  == '')
	{
		$theme->insertContent($formSave);
	}
?>
