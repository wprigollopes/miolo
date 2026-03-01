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
    $navbar->AddOption(_M('Indexing project @1',$module,$item),$module,"$home:index&item=$item&topic=$topic");
    $handler = $MIOLO->GetActionURL($module,"$home:index&item=$item");
    $ui = $MIOLO->GetUI();

    $Business = $MIOLO->GetBusiness($module, 'Topics');
   
    if ($item)
        $content = $Business->GenerateHtml($item);
    
    $theme->SetContent($content);
?>
