<?

$MIOLO->CheckAccess('common',A_SYSTEM,true);

$home = 'main:admin';

$navbar->AddOption('Modules',$module,$home.':modules_list');

$subMenu = & $theme->GetMenu('admin');
$subMenu->AddSeparator();
$subMenu->AddOption('Insert Module',$module,$home.':modules_insert');

$MIOLO->GetUI();

$business = $MIOLO->GetBusiness($module,'admin');

$listing = new Listing();
$listing->SetTitle($item?_M('Modules of user "@1"','miolo',strtoupper($item)):_M('Modules'));
$listing->SetLabels(array(new ListingColumn('Name'),
                          new ListingColumn('Description')));
$listing->SetData($business->GetModules($item));
$listing->AddError($business->GetErrors());

$listing->AddActionIcon('Edit','update.gif',$MIOLO->GetActionURL($module,$home.':modules_update','%0%'));
$listing->AddActionIcon('Delete','delete.gif',$MIOLO->GetActionURL($module,$home.':modules_delete','%0%', array('name'=>'%1%')));
$listing->AddActionText('','Users',$MIOLO->GetActionURL($module,$home.':users_list','%0%'));

$theme->SetContent($listing);

?>
