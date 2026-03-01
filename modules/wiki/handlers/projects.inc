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
// |                                                                 |
// | History: Initial Revision                                       |
// +-----------------------------------------------------------------+
	
    // access rights: the last parameter (true) call an error page
    // informing user is not authorized to access
    //$MIOLO->CheckAccess($module,'update',true);

    // where are we?
    $home = 'main:projects';

    $navbar->AddOption('Projects',$module,$home);

	$ui   = $MIOLO->GetUI();
    if ($item) $menu->AddUserOption('insert',"Permissions",$module,"$home:permissions",$item);

    if ( ! $MIOLO->InvokeHandler($module,'/projects/' . $context->ShiftAction()) )
    {
        $menu->SetTitle("Projects");
        $menu->AddUserOption('insert',"Include",$module,"$home:include");
    }

?>
