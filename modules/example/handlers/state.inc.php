<?
// limpa o conteúdo da página
    $theme->clearContent();

// obter acesso as classes de Interface de Usuario
    $ui = $MIOLO->getUI();
    
// adicionando link ao barra de navegação
    $navbar->addOption('State', $module, $self);

// cria o formulário, definido no arquivo example/forms/FrmSession.class
    $formState = $ui->getForm($module,'frmState');

// coloca o conteúdo da página (primeiro o menubar, depois o $page)
if ($theme->getContent()  == '')
{
    $theme->insertContent($formState);
}

?>
