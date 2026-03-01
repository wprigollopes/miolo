<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$MIOLO->Assert($item,"Parameter 'item' (group id) not informed!");

$home = 'main:admin';

$navbar->AddOption('Permissions',$module,$home.':groups_perms');

$ui = $MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$group = $business->GetGroup($item);

$MIOLO->Assert($group,_M('Error: Group @1 does not exist','miolo',$item));

$handler = $MIOLO->GetActionURL($module,"$home:groups_perms",$item);
$form = $ui->GetForm($module,'GroupPermissionsForm',$group);
$form->SetAction($handler);

if ( Form::IsSubmitted() )
{
    $business = $MIOLO->GetBusiness($module,'admin');
    
    $data = $form->GetData(new CommonGroup());
    
    if ( $business->UpdateGroupPermissions($data->module,
                                          $data->id,
                                          $data->permissions) )
    {
        $MIOLO->Information('The group permissions have been updated successfully.',
                            $MIOLO->GetActionURL($module,$home.':groups_perms',$item));
        return true;
    }
    
    $form->AddError($business->GetErrors());
}

$theme->SetContent($form); 

?>
