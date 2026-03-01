<?php
// Proxy server for weather component
//$proxy = 'http://proxy.fates.tche.br:3128';

$navbar->AddOption('Components',$module,'main:components');

$subMenu = & $theme->GetMenu('Components');

$subMenu->AddOption('Weather Lajeado',$module,'main:components','weather_lajeado');
$subMenu->AddOption('Weather Estrela',$module,'main:components','weather_estrela');
$subMenu->AddOption('Weather Imigrante',$module,'main:components','weather_imigrante');
$subMenu->AddOption('Weather Porto Alegre',$module,'main:components','weather_poa');
$subMenu->AddOption('Weather Campinas',$module,'main:components','weather_campinas');
$subMenu->AddOption('Weather Rio de Janeiro',$module,'main:components','weather_rj');
$subMenu->AddOption('Weather Fernando Noronha',$module,'main:components','weather_fernando');
$subMenu->AddSeparator();
$subMenu->AddOption('Ticker',$module,'main:components','ticker');
$subMenu->AddOption('Counter',$module,'main:components','counter');


if ( $item == 'ticker' )
{
    $ticker = $theme->UseComponent('ticker');
    $ticker->AddMessage('01/01/2003','Ano novo, vida nova... vamos à luta.. ;^)','#');
    $ticker->AddMessage('10/12/2002','Liberada a versão RC4.1 do MIOLO','#');
    $ticker->AddMessage('21/05/2002','Thomas Spriestersbach viaja para Weyer (Alemanha)','#');
    $ticker->AddMessage('13/03/2002','Alann Gabriel Kuhn Gärtner nasceu','#');
    $ticker->AddMessage('01/12/2001','Vilson Cristiano Gärtner viaja para Alemanha','#');
    
    $theme->AddElement( new ThemeBox('News',$ticker), 'content' );
}
else if ( $item == 'weather_lajeado' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX2368');
    
    $theme->SetContent( new ThemeBox('O Tempo em Lajeado/RS',$weather) );
}
else if ( $item == 'weather_estrela' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX1806');
    
    $theme->SetContent( new ThemeBox('O Tempo em Estrela/RS',$weather) );
}
else if ( $item == 'weather_poa' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX0186');
    
    $theme->SetContent( new ThemeBox('O Tempo em Porto Alegre/RS',$weather) );
}
else if ( $item == 'weather_campinas' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX0050');
    
    $theme->SetContent( new ThemeBox('O Tempo em Campinas/SP',$weather) );
}
else if ( $item == 'weather_rj' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX0201');
    
    $theme->SetContent( new ThemeBox('O Tempo no Rio de Janeiro/RJ',$weather) );
}
else if ( $item == 'weather_fernando' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX1841');
    
    $theme->SetContent( new ThemeBox('O Tempo em Fernando de Noronha',$weather) );
}
else if ( $item == 'weather_imigrante' )
{
    $weather = $theme->UseComponent('weather');
    $weather->SetProxy($proxy);
    $weather->SetCode('BRXX2052');
    
    $theme->SetContent( new ThemeBox('O Tempo em Imigrante/RS',$weather) );
}
else if ( $item == 'counter' )
{
    $counter = $theme->UseComponent('counter');
    
    $theme->AddElement( new ThemeBox('Visits',$counter),'menu' );
}
else
{
    $theme->SetContent( new ThemeBox('Components','<p align="center">Choose an option from the <i>Components</i> menu.</p>') );
}

return true;

?>
