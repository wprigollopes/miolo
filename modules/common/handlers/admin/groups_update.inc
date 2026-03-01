<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Update Group',$module,$home.':groups_update');

$MIOLO->Assert($item,"Parameter 'item' (group login) not informed!");

$ui = $MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$handler = $MIOLO->GetActionURL($module,"$home:groups_update",$item);
$form = $ui->GetForm($module,'GroupForm', $business->GetGroup($item));
$form->SetAction($handler);

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();

    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->UpdateGroup($data) )
        {
            $MIOLO->Information('The group has been updated successfully.',
                                $MIOLO->GetActionURL($module,$home.':groups_list'));
            return true;
        }
    }
}

$form->AddError($business->GetErrors());

$theme->SetContent($form);

?>
