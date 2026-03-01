<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Modules',$module,$home.':groups_list');

$subMenu = & $theme->GetMenu('admin');
$subMenu->AddSeparator();
$subMenu->AddOption('Insert Group',$module,$home.':groups_insert');

$MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$listing = new Listing();
$listing->SetTitle($item?_M('Groups of module "@1"','miolo',strtoupper($item)):_M('Groups'));
$listing->SetLabels(array(new ListingColumn('Id',null,'left'),
                          new ListingColumn('Description'),
                          new ListingColumn('Module')));
$listing->SetData($business->GetGroups($item));
$listing->AddError($business->GetErrors());

$listing->AddActionIcon('Edit','update.gif',$MIOLO->GetActionURL($module,$home.':groups_update','%0%'));
$listing->AddActionIcon('Delete','delete.gif',$MIOLO->GetActionURL($module,$home.':groups_delete','%0%', array('name'=>'%1%')));
$listing->AddActionText('','Rights',$MIOLO->GetActionURL($module,$home.':groups_perms','%0%'));
$listing->AddActionText('','Modules',$MIOLO->GetActionURL($module,$home.':modules_list','%0%'));

$theme->SetContent($listing);

?>
