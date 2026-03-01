<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Delete Module', $module, $home.':modules_delete');

$MIOLO->Assert($item,"Parameter 'item' (module name) not informed!");

$MIOLO->GetUI();

$confirmed = $_GET['confirmed'];

if ( isset($confirmed) )
{
    if ( $confirmed == 'yes' )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->DeleteModule($item) )
        {
            $url = $MIOLO->GetActionURL($module, $home.':modules_list');
            $theme->SetContent( Prompt::Information('Module sucessfully deleted.', $url) );
        }
        else
        {
            $err = $business->GetErrors();;
            $theme->SetContent( Prompt::Error( $err) );
        }
    }
    else
    {
        $url = $MIOLO->GetActionURL($module, $home.':modules_list');
        $theme->SetContent( Prompt::Information('Module Not deleted.', $url) );
    }
}
else
{
    $url_yes = $MIOLO->GetActionURL($module, $home.':modules_delete', $item, array('confirmed'=>'yes'));
    $url_no  = $MIOLO->GetActionURL($module, $home.':modules_delete', $item, array('confirmed'=>'no'));
    
    $content = Prompt::Question("Are you sure you want to delete module '$item' ($name)<br> and the users's rights for this module?", $url_yes, $url_no );
    
    $theme->SetContent( $content );
}

?>
