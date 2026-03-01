<?
// limpa o conteúdo da página
    $theme->ClearContent();

// obter acesso as classes de Interface de Usuario
    $ui = $MIOLO->GetUI();
    
// adicionando link ao barra de navegação
    $navbar->AddOption('MultiSelectionField', $module, $self);

// cria o formulário, definido no arquivo tutorial/forms/FrmControles.class
    $form = $ui->GetForm($module,'frmMultiSelectionField');
    
// coloca o conteúdo da página 
	$theme->InsertContent($form);

?>
