<?
	/**
	 *	 limpa o conteÃºdo da pÃ¡gina
	 */
    $theme->clearContent();
	
	/**
	 * obter acesso as classes de Interface de Usuario
	 */
    $ui = $MIOLO->getUI();
    
	/**
	 * adicionando link ao barra de navegaÃ§Ã£o
	 */
    $navbar->addOption('Save', $module, $self);

	/**
	 * cria o formulÃ¡rio, definido no arquivo example/forms/FrmSession.class
	 */
    $formSave = $ui->getForm($module,'frmSave');

	/**
	 * coloca o conteÃºdo da pÃ¡gina (primeiro o menubar, depois o $page)
	 */
	if ($theme->getContent()  == '')
	{
		$theme->insertContent($formSave);
	}
?>
