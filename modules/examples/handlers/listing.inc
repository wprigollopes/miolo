<?
    $navbar->AddOption('Listing',$module,'main:listing');
    
    $MIOLO->GetUI();
    
    $data = array(array(1,'Rio Grande do Sul','Brasil','BR'),
                  array(2,'Paraná','Brasil','BR'));
    
    $listing1 = new Listing();
    $listing1->SetTitle('Listing com todas colunas');
    $listing1->SetLabels(array('ID','Estado','Pais','Codigo'));
    $listing1->SetData($data);
    
    $theme->SetContent($listing1);
    
    $listing2 = new Listing();
    $listing2->SetTitle('Listing com colunas escondidas');
    $listing2->SetLabels(array(null,'Estado','Pais',null));
    $listing2->SetData($data);
    
    $theme->AppendContent($listing2);
?>
