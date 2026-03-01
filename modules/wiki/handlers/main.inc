<?
// +-----------------------------------------------------------------+
// | Wiki Development Team UNIVATES - Centro Universitário           |  
// +-----------------------------------------------------------------+
// |      Copyleft (l) 2002 UNIVATES, Lajeado/RS - Brasil            |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolowiki.codigolivre.org.br                       |
// +-----------------------------------------------------------------+
// | Created: 2002/12/16 Alexandre Heitor Schmidt xandehs@univates.br|
// |                     Nasair Júnior da Silva   njunior@univates.br|
// | Changed: 2003/02/13 Thomas Spriestersbach (ts@interact2000.com.br)|
// +-----------------------------------------------------------------+

    global $MIOLOCONF;
    
    $home = 'main';
    
    $theme->SetTitle(_M('WIKI - A Collaborative Authoring System',$module));
    
    $navbar->AddOption('Wiki','wiki',$home);
    
    $menu->Clear();
    $menu->SetTitle('Wiki');
    $menu->AddOption(_M('View Projects',$module),$module,$home);
    $menu->AddUserOption('admin',_M('Insert a project',$module),$module,"$home:projects:include");
    $menu->AddUserOption('admin',_M('Edit Project',$module),$module,"main:projects:edit",$item);
    $menu->AddSeparator();
    $menu->AddOption(_M('Search Wiki',$module),$module,"$home:search");
    $menu->AddSeparator();
    
    $ui = $MIOLO->GetUI();
    
    if ( ! $MIOLO->InvokeHandler($module,$context->ShiftAction()) )
    {
        // page intro
        $intro = "<div align=\"center\"><br>" .
                 "<font face=\"Arial\" size=\"5\"><i><u>"._M("Welcome to the @1", $module, "</u></i>") .
                 "</font><br>" .
                 "<font face=\"Arial\" size=\"8\"><b>Wiki</b></font><br><br>" .
                 "</div>";
	   
        // page navigator setup
        $url = $MIOLO->GetActionURL($module,"$home");
        $pn = new PageNavigator(10,$url);
        
        // get data
        $projects = $MIOLO->GetBusiness($module, 'Projects' );
        
        $rs = $projects->GetAllProjects($pn->GetQueryRange());
        
        // build listing
        $listing = new Listing();
        $listing->SetTitle(_M("Registered Projects",$module));
        
        $listing->SetLabels( array( null, //new ListingColumn(_M("ID",$module),null),
                                    _M("Project Name",$module),
                                    null, //new ListingColumn(_M("Created on",$module),null),
                                    _M("Last update",$module),
                                    _M("By",$module),
                                    _M("Responsible",$module) ) );

        $listing->AddAction(_M("Open",$module),"/modules/$module/images/open.gif",
                            $MIOLO->GetActionURL($module,"$home:topic&item=%0%") );
        $listing->SetData($rs);

        // listing footer
        $sb = new StatusBar();
        $sb->AddInfo( $pn->GetTotalRows() . _M(" project(s) in ",$module) .
                      $pn->GetTotalPages() . _M(" page(s).",$module) );
      
        $listing->SetFooter( array( $pn,$sb ) );

        $content = array( $intro, $listing );
        $theme->SetContent( $content );
    }
    
    $bus = $MIOLO->GetBusiness($module,'Settings');
    
    $settings = $bus->GetSettings();
    
    if (!$MIOLO->login)
    {
        $sys_menu = & $theme->GetMenu('login');
        $sys_menu->AddOption(_M("Login",$module),'common','login',"&return_to=".urlencode($handler));
    }
    
?>
