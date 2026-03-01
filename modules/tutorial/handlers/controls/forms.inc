<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('Form Controls', $module, $self);
$theme->BreakContent();
$panel = new Panel('pnlDummy','Forms Controls');

$controls = array(
    new Text('',"Panel: Uh! i'm inside a panel!"),
);
$panel->AddControl($controls,'100%','clear');
$theme->AppendContent($panel);
$src = new ViewSource(__FILE__);
$theme->AppendContent($src);

?>