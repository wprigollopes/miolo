<?
    $navbar->AddOption('Layout Test',$module,'main:layout');
    
    $ui = $MIOLO->GetUI();
    
    $form = new Form("Teste MultiTextField");
    
    $layout = Form::GetFormValue('layout',0);
    
    $selection = new Selection('Layout','layout',$layout,
                               array(0=>'Vertical',1=>'Horizontal'));
    
    $fields = array($selection,
                    new TextField('A1','a1'),
                    new TextField('A2','a2'),
                    new TextField('A3','a3'),
                    new TextField('B','b'));

    $fields1 = array(new TextField('.','a1'),
                    new TextField('.','a1'),
                    new TextField('.','a2'),
                    new TextField('.','a3'),
                    new TextField('.','b'));
                    
    $form->SetAction( MIOLO::GetCurrentURL() );

    if ( $layout == 1 )
    {
        $fields = array($fields, $fields1);
    }
    
    $form->SetFields($fields);
    
    $theme->SetContent($form);
    
    if ( Form::IsSubmitted() )
    {
      $MIOLO->Dump($HTTP_POST_VARS,__FILE__,__LINE__);
    }
?>
