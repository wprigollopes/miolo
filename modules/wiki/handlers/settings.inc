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
    $navbar->AddOption(_M('Settings',$module),$module,"$home:settings");
    $handler = $MIOLO->GetActionURL($module,"$home:settings");
    $ui = $MIOLO->GetUI();

    $Business = $MIOLO->GetBusiness($module, 'Settings');
   
    $form = $ui->GetForm($module,'SettingsForm',$Business->GetSettings());
    if (Form::IsSubmitted())
    {
        $data = $form->GetData();
        $content = $Business->SetSettings($data);
        $content = $MIOLO->Information(_M("The settings were stored with success!!!",$module),$MIOLO->GetACtionURL($module,'main'));
    }
    else
        $content = $form;
    
    $theme->SetContent($content);
?>
