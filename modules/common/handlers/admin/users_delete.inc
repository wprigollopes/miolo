<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Delete User', $module, $home.':users_delete');

$MIOLO->Assert($item,"Parameter 'item' (user login) not informed!");

$MIOLO->GetUI();

$confirmed = $_GET['confirmed'];

if ( isset($confirmed) )
{
    if ( $confirmed == 'yes' )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( $business->DeleteUser($item) )
        {
            $url = $MIOLO->GetActionURL($module, $home.':users_list');
            $theme->SetContent( Prompt::Information('User sucessfully deleted.', $url) );
        }
        else
        {
            $err = $business->GetErrors();;
            $theme->SetContent( Prompt::Error( $err) );
        }
    }
    else
    {
        $url = $MIOLO->GetActionURL($module, $home.':users_list');
        $theme->SetContent( Prompt::Information('User Not deleted.', $url) );
    }
}
else
{
    $url_yes = $MIOLO->GetActionURL($module, $home.':users_delete', $item, array('confirmed'=>'yes'));
    $url_no  = $MIOLO->GetActionURL($module, $home.':users_delete', $item, array('confirmed'=>'no'));
    
    $content = Prompt::Question("Are you sure you want to delete user '$name', login '$item' <br> and his rights?", $url_yes, $url_no );
    
    $theme->SetContent( $content );
}

?>
