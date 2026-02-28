<?
// limpa o conteÃºdo da pÃ¡gina
    $theme->clearContent();

// obter acesso as classes de Interface de Usuario
    $ui = $MIOLO->getUI();
    
// adicionando link ao barra de navegaÃ§Ã£o
    $navbar->addOption('State', $module, $self);

// cria o formulÃ¡rio, definido no arquivo example/forms/FrmSession.class
    $formState = $ui->getForm($module,'frmState');

// coloca o conteÃºdo da pÃ¡gina (primeiro o menubar, depois o $page)
if ($theme->getContent()  == '')
{
    $theme->insertContent($formState);
}

?>
