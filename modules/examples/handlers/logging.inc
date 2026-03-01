<?
    $navbar->AddOption('Layout Test',$module,'main:layout');
    
    $ui = $MIOLO->GetUI();
    
    $form = new Form("Logging Test");
    
    $fields = array(new TextField('Categoria','categoria'),
                    new TextField('Mensagem','mensagem',false,40));

    $form->SetFields($fields);
    
    $theme->SetContent($form);
    
    if ( Form::IsSubmitted() )
    {
        if ( $form->ValidateAll() )
        {
            $categoria = strtoupper(Form::GetFormValue('categoria'));
            $mensagem  = Form::GetFormValue('mensagem');
            
            $MIOLO->LogMessage('[' . $categoria . ']' . $mensagem);
        }
    }
?>
