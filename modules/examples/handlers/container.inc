<?

$container = new Container();

$container->AddElement(new ThemeElement('<center>News Ticker</center>'),'top');

$container->AddElement(new NewsBox('Box&nbsp;1'),'left');
$container->AddElement(new ThemeElement('Box&nbsp;2'),'left');
$container->AddElement(new ThemeElement('Box&nbsp;3'),'left');

$container->AddElement(new ThemeElement('Manchete&nbsp;1'),'center');
$container->AddElement(new ThemeElement('Manchete&nbsp;2'),'center');
$container->AddElement(new ThemeElement('Manchete&nbsp;3'),'center');

$container->AddElement(new ThemeElement('Box&nbsp;1'),'right');
$container->AddElement(new ThemeElement('Box&nbsp;2'),'right');
$container->AddElement(new ThemeElement('Box&nbsp;3'),'right');

$container->AddElement(new ThemeElement('<center>Impressum</center>'),'bottom');

$theme->SetContent($container);

?>
