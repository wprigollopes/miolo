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
    
    $home = 'main';
    $navbar->AddOption(_M('Generate Formats',$module),$module,"$home:generate&item=$item");
    
    $handler = $MIOLO->GetActionURL($module,"$home:generate&item=$item&format=$format");
    $ui = $MIOLO->GetUI();
	
    $formats = array(
		"all"=>_M("All the formats",$module),
		"pdf"=>_M("Portable Document",$module),
		"txt"=>_M("Text",$module),
		"dvi"=>_M("DVI",$module),
		"ps"=>_M("Post Script",$module),
		"docbook"=>_M("DocBook",$module),
		"xml"=>_M("XML",$module),
		"sgml"=>_M("Sgml",$module),
        "html"=>_M("Html",$module),
		"tar.gz"=>_M("TarGz Package",$module));
    
    if ($format)
        $navbar->AddOption(_M('Generate @1',$module,$formats[$format]),$module,"$home:generate&item=$item&format=$format");

    $Business = $MIOLO->GetBusiness($module, 'Topics');
    $Business2 =$MIOLO->GEtBusiness($module, 'Projects');
   
    if ($item && $format)
    {
        $project = $Business2->GetProject($item);
        if ($format == 'html')
            $data = $Business->GenerateHtml($project);
        else
            $data = $Business->GenerateDocBook($project,$format);
	    if (is_array($data) || $format == 'html')
        {
	        $content = "<ul>";
                foreach ($data as $id=>$dat)
                {
                    if (strpos($dat,'/') ===0)
                        $dat_ = substr($dat,1);
                    else $dat_ = $dat;
                    $dat_ = str_replace('%2F','/',urlencode($dat_));
	    	        $content .= "<li><a href=\"".$dat_."\">$id</a></li>";
                }
            $content .= "</ul>";
        }
        else
            if ($format && $format != 'all')
                $content = $MIOLO->Information(_M("The @1 format couldn't be generated!",$module,$formats[$format]),"javascript:history.go(-1)");
            else
                $content = $MIOLO->Information(_M("None format could be generated!",$module),"javascript:history.go(-1)");
                
    }
    else
    {
	    $content = "<ul>";
	    foreach ($formats as $dat=>$title)
		    $content .= "<li><a href=\"{$handler}{$dat}\">$title</a></li>";
    	$content .= "</ul>";

    }
    
    $theme->SetContent($content);
?>
