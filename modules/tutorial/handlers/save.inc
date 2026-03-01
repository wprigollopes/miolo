<?
	/**
	 *	 limpa o conteúdo da página
	 */
    $theme->ClearContent();
	
	/**
	 * obter acesso as classes de Interface de Usuario
	 */
    $ui = $MIOLO->GetUI();
    
	/**
	 * adicionando link ao barra de navegação
	 */
    $navbar->AddOption('Save', $module, $self);

	/**
	 * cria o formulário, definido no arquivo tutorial/forms/FrmSession.class
	 */
    $formSave = $ui->GetForm($module,'frmSave');

	/**
	 * coloca o conteúdo da página (primeiro o menubar, depois o $page)
	 */
	if ($theme->GetContent()  == '')
	{
		$theme->InsertContent($formSave);
	}
?>
