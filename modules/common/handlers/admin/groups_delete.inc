<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Delete Group', $module, $home.':groups_delete');

$MIOLO->Assert($item,"Parameter 'item' (group login) not informed!");

$MIOLO->GetUI();

if ( isset($confirmed) )
{
    if ( $confirmed == 'yes' )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->DeleteGroup($item) )
        {
            $url = $MIOLO->GetActionURL($module, $home.':groups_list');
            $theme->SetContent( Prompt::Information('Group sucessfully deleted.', $url) );
        }
        else
        {
            $err = $business->GetErrors();;
            $theme->SetContent( Prompt::Error( $err) );
        }
    }
    else
    {
        $url = $MIOLO->GetActionURL($module, $home.':groups_list');
        $theme->SetContent( Prompt::Information('Group Not deleted.', $url) );
    }
}
else
{
    $url_yes = $MIOLO->GetActionURL($module, $home.':groups_delete', $item, array('confirmed'=>'yes'));
    $url_no  = $MIOLO->GetActionURL($module, $home.':groups_delete', $item, array('confirmed'=>'no'));
    
    $content = Prompt::Question("Are you sure you want to delete group '$name', login '$item' <br> and his rights?", $url_yes, $url_no );
    
    $theme->SetContent( $content );
}

?>
