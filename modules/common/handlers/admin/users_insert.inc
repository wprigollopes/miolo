<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Users',$module,$home.':users_insert');

$ui = $MIOLO->GetUI();

$form = $ui->GetForm($module,'UserForm');
$form->SetAction( MIOLO::GetCurrentURL() );

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();

    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->InsertUser($data) )
        {
            $MIOLO->Information('The user has been inserted successfully.',
                                $MIOLO->GetActionURL($module,$home.':users_list'));
            return true;
        }
        
        $form->AddError( $business->GetErrors() );
    }
}

$theme->SetContent($form);

?>
