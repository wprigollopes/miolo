<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Groups',$module,$home.':groups_insert');
$handler = $MIOLO->GetActionURL($module,"$home:groups_insert");

$ui = $MIOLO->GetUI();

$form = $ui->GetForm($module,'GroupForm');
$form->SetAction($handler);

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();

    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->InsertGroup($data) )
        {
            $MIOLO->Information('The group has been inserted successfully.',
                                $MIOLO->GetActionURL($module,$home.':groups_list'));
            return true;
        }
        
        $form->AddError( $business->GetErrors() );
    }
}

$theme->SetContent($form);

?>
