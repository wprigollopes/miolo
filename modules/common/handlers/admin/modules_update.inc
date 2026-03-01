<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Update',$module,$home.':modules_update');

$MIOLO->Assert($item,"Parameter 'item' (module name) not informed!");

$ui = $MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$form = $ui->GetForm($module,'ModuleForm',$business->GetModule($item));
$form->SetAction( MIOLO::GetCurrentURL() );

if ( Form::IsSubmitted() )
{
    $data = $form->GetData();
    
    if ( $data )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->UpdateModule($data) )
        {
            $MIOLO->Information('The module has been updated successfully.',
                                $MIOLO->GetActionURL($module,$home.':modules_list'));
            return true;
        }
    }
}

$form->AddError($business->GetErrors());

$theme->SetContent($form); 

?>
