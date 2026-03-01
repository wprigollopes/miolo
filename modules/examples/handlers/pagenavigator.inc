<?
    $navbar->AddOption('Page Navigator',$module,'main:pagenavigator');
    
    $MIOLO->GetUI();
    
    for ( $i=0; $i<10; $i++ )
    {
        $data[][0] = $i + 1;
    }
    
    $pn = new PageNavigator(10);
    $qr = & $pn->GetQueryRange();
    $qr->total = 11;
    
    $listing = new Listing();
    $listing->SetTitle('PageNavigator Test');
    $listing->SetLabels(array('Test'));
    $listing->SetData($data);
    $listing->SetFooter($pn);
    
    $theme->SetContent($listing);
?>
