<?
    $navbar->AddOption('Menu',$module,'main:menu');
    
    $MIOLO->GetUI();
    
    $theme->SetContent(new ThemeElement('<center><h1>Submenu Test</h1></center>'));
    
    $myMenu = new ThemeMenu();
    $myMenu->SetTitle('Main Menu');
    $myMenu->AddOption('Option 1',$module);
    
    $sub1 = new ThemeMenu();
    $sub1->SetTitle("Submenu 1");
    $sub1->AddOption('Option 1.1',$module);
    
    $sub2 = new ThemeMenu();
    $sub2->SetTitle("Submenu 2");
    $sub2->AddOption('Option 2.1',$module);
    
    $sub3 = new ThemeMenu();
    $sub3->SetTitle("Submenu 3");
    $sub3->AddOption('Option 3.1',$module);
    
    $sub2->AddMenu($sub3);
    $sub1->AddMenu($sub2);
    $myMenu->AddMenu($sub1);
    
    $theme->AppendContent($myMenu);
    
    $m = $theme->GetMenu('My Menu');
    $m->AddMenu($myMenu);
?>
