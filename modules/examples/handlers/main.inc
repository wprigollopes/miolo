<?php
    $navbar->AddOption('Examples',$module,'main');
    
    $menu->SetTitle('Some Examples');
    $menu->AddOption('Formfields',$module,'main:formfields');
    $menu->AddOption('Components',$module,'main:components');
    $menu->AddSeparator();
    $menu->AddOption('TabbedForm',$module,'main:tabbed');
    $menu->AddOption('TabbedForm 2',$module,'main:tabbed2');
    $menu->AddSeparator();
    $menu->AddOption('Javascript Validation',$module,'main:validation');
    $menu->AddOption('Lookup',$module,'main:lookup');
    $menu->AddOption('Checkbox Group',$module,'main:checkboxes');
    $menu->AddOption('Input Grid',$module,'main:inputgrid');
    $menu->AddOption('Layout Test',$module,'main:layout');
    $menu->AddOption('Listing Test',$module,'main:listing');
    $menu->AddOption('Page Navigator',$module,'main:pagenavigator');
    $menu->AddOption('Menu Test',$module,'main:menu');
    $menu->AddOption('Multi Text Fields',$module,'main:multitext');
    $menu->AddOption('Quoting Test',$module,'main:quoting');
    $menu->AddOption('Barcode',$module,'main:barcode');
    $menu->AddOption('Logging Test',$module,'main:logging');
    $menu->AddOption('Container',$module,'main:container');
    
    $a = $context->ShiftAction();
    
    if ( $a == 'source' )
    {
        $theme->SetContent( new FileContent($MIOLO->GetModulePath($module,"handlers/$item.inc"),true) );
    }
    else
    {
        if ( $a )
        {
            $menu->AddSeparator();
            $menu->AddOption('View Source',$module,'main:source',$a);
        }
        
        $MIOLO->InvokeHandler($module,$a);
    }
?>
