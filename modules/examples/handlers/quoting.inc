<?
    $navbar->AddOption('Quoting Test',$module,'main:quoting');
    
    $ui = $MIOLO->GetUI();
    
    $form = new TabbedForm("Teste &quot;Quotes&quot;");

    $form->AddPage('Page 1',array(new TextField('Teste','test1',$form->GetFormValue('test1'),40)));
    $form->AddPage('Page 2',array(new TextField('Teste','test2',$form->GetFormValue('test2'),40)));
    
    if ( ! $form->GetFormValue('test1') )
    {
        $form->SetFormValue('test1','869.0"1992"');
        $form->SetFormValue('test2','968.0"2002"');
    }
    
    $theme->SetContent($form);
?>
