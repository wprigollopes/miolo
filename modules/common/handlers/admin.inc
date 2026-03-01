<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

/*
create table cmn_modules
(
    name        varchar(20)  primary key,
    description varchar(120) not null,
    rights      text
);
*/

$navbar->AddOption('Administration',$module,'main:admin');

$subMenu = & $theme->GetMenu('admin');
$subMenu->SetTitle('Administration');
$subMenu->AddOption('Modules',$module,'main:admin:modules_list');
$subMenu->AddOption('Users',$module,'main:admin:users_list');
$subMenu->AddOption('Groups',$module,'main:admin:groups_list');

$MIOLO->InvokeHandler($module,'admin/' . $context->ShiftAction());

?>
