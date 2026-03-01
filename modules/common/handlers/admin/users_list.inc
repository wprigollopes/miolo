<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Modules',$module,$home.':users_list');

$subMenu = & $theme->GetMenu('admin');
$subMenu->AddSeparator();
$subMenu->AddOption('Insert User',$module,$home.':users_insert');

$MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$listing = new Listing();
$listing->SetTitle($item?_M('Users of module "@1"','miolo',strtoupper($item)):_M('Users'));
$listing->SetLabels(array(new ListingColumn('Login',null,'left'),
                          new ListingColumn('Name'),
                          new ListingColumn('Alias'),
                          new ListingColumn('EMail')));
$listing->SetData($business->GetUsers($item));
$listing->AddError($business->GetErrors());

$listing->AddActionIcon('Edit','update.gif',$MIOLO->GetActionURL($module,$home.':users_update','%0%'));
$listing->AddActionIcon('Delete','delete.gif',$MIOLO->GetActionURL($module,$home.':users_delete','%0%', array('name'=>'%1%')));
$listing->AddActionText('','Rights',$MIOLO->GetActionURL($module,$home.':users_perms','%0%'));
$listing->AddActionText('','Modules',$MIOLO->GetActionURL($module,$home.':modules_list','%0%'));

$theme->SetContent($listing);

?>
