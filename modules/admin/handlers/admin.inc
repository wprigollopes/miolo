<?

$perms->CheckAccess('admin',A_ACCESS,true);

$navbar->AddOption('Administração',$module,'main:admin');

$admMenu = & $theme->GetMenu('admin');
$admMenu->SetTitle('Administração');
$admMenu->AddOption('Transações',$module,'main:admin:transaction');
$admMenu->AddOption('Usuários',$module,'main:admin:user');
$admMenu->AddOption('Senha',$module,'main:admin:password');
$admMenu->AddOption('Grupos',$module,'main:admin:groups');
$admMenu->AddOption('Log',$module,'main:admin:log');


$handled = $MIOLO->InvokeHandler($module, 'admin/' . $context->ShiftAction());
if (! $handled)
{
   $MIOLO->InvokeHandler($module, 'admin/users');
}
?>
