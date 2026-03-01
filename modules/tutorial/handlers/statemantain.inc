<?
// limpa o conteúdo da página
    $theme->ClearContent();

// obter acesso as classes de Interface de Usuario
    $ui = $MIOLO->GetUI();
    
// adicionando link ao barra de navegação
    $navbar->AddOption('Mantain State', $module, $self);

// cria o formulário, definido no arquivo tutorial/forms/FrmSession.class
    $formState = $ui->GetForm($module,'frmStateMantain');

// coloca o conteúdo da página (primeiro o menubar, depois o $page)
if ($theme->GetContent()  == '')
{
    $theme->InsertContent($formState);
}

?>
