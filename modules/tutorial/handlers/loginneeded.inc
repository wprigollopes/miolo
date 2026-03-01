<?

if ($MIOLO->GetAuth()->isLogged())
{
    $MIOLO->GetPerms()->CheckAccess('LoginNeeded',A_ACCESS,true);
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Login Needed', $module, $self);
    $text = new MLabel('A simple example of a handler that needs a logged/authenticated user');
    $theme->AppendContent($text);
}
else
{
    $ui = $MIOLO->GetUI();
    $form = $ui->GetForm('admin','frmLogin');
    $form->SetFieldValue('return_to', $url);
    $theme->SetContent($form);
}
?>
