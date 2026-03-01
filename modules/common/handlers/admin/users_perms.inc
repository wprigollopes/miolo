<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$MIOLO->Assert($item,"Parameter 'item' (user login) not informed!");

$home = 'main:admin';

$navbar->AddOption('Permissions',$module,$home.':users_perms');

$ui = $MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$user = $business->GetUser($item);

$MIOLO->Assert($user,_M('Error: User @1 does not exist','miolo',$item));

$form = $ui->GetForm($module,'UserPermissionsForm',$user);
$form->SetAction( MIOLO::GetCurrentURL() );

if ( Form::IsSubmitted() )
{
    $business = $MIOLO->GetBusiness($module,'admin');
    
    $data = $form->GetData(new CommonUser());
    
    if ( $business->UpdateUserPermissions($data->module,
                                          $data->login,
                                          $data->permissions) )
    {
        $MIOLO->Information('The user permissions have been updated successfully.',
                            $MIOLO->GetActionURL($module,$home.':users_perms',$item));
        return true;
    }
    
    $form->AddError($business->GetErrors());
}

$theme->SetContent($form); 

?>
