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
    $navbar->AddOption(_M('Searching for',$module),$module,"$home:search");
    $handler = $MIOLO->GetActionURL($module,"$home:search&topic=$topic&project=$project");
    $ui = $MIOLO->GetUI();

    $Business = $MIOLO->GetBusiness($module, 'Topics');
   
    $form = $ui->GetForm($module,'SearchForm');
    if (Form::IsSubmitted())
    {
        $data = $form->GetData();
        $content = $Business->Search($data);
    }
    else
        $content = $form;
    
    $theme->SetContent($content);
?>
