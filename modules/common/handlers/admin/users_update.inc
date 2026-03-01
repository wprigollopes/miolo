<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Update User',$module,$home.':users_update');

$MIOLO->Assert($item,"Parameter 'item' (user login) not informed!");

$ui = $MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$form = $ui->GetForm($module,'UserForm', $business->GetUser($item));
$form->SetAction( MIOLO::GetCurrentURL() );

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();

    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->UpdateUser($data) )
        {
            $MIOLO->Information('The user has been updated successfully.',
                                $MIOLO->GetActionURL($module,$home.':users_list'));
            return true;
        }
    }
}

$form->AddError($business->GetErrors());

$theme->SetContent($form);

?>
