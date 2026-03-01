<?
    $navbar->AddOption('MultiText Fields',$module,'main:multitext');
    
    $ui = $MIOLO->GetUI();
    
    $form = new Form("Teste MultiTextField");
    
    // define a row of multitext fields
    $row = array(new MultiTextField('Autor','autor',100,null,false),   // sem botoes
                 new MultiTextField('Titulo','titulo',200,null,true),  // com botoes
                 new MultiTextField('Editora','editora',100));
    
    $fields = array($row,$row,$row,                                       // just for fun: replicate rows
                    new MultiTextField('Autor','autor',100,null,false),   // sem botoes
                    new MultiTextField('Titulo','titulo',200,null,true),  // com botoes
                    new MultiTextField('Editora','editora',100));         // sem botoes
    
    $form->SetFields($fields);
    
    // isto eh importante para suporte dos elementos dos forms,
    // que precisam de auxilio do JS para funcionar
    $theme->AddScript('common.js'); 
    
    $theme->SetContent($form);
    
    if ( Form::IsSubmitted() )
    {
      $MIOLO->Dump($HTTP_POST_VARS,__FILE__,__LINE__);
      
      $form->Error('Deprecated test');
    }
?>
