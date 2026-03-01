<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Modules',$module,$home.':modules_insert');

$ui = $MIOLO->GetUI();

$form = $ui->GetForm($module,'ModuleForm');
$form->SetAction( MIOLO::GetCurrentURL() );

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();
    
    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->InsertModule($data) )
        {
            $MIOLO->Information('The module has been inserted successfully.',
                                $MIOLO->GetActionURL($module,$home.':modules_list'));
            return true;
        }
        
        $form->AddError($business->GetErrors());
    }
}

$theme->SetContent($form); 

?>
