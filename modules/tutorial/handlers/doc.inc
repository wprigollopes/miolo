<?
$ui = $MIOLO->GetUI();
$navbar->AddOption('File Source', $module, $self);
$theme->ClearContent();
$src = new FileContent($item, true);
$div = new Div('',$src);
$div->AddStyle('margin-left','20px');
$div->AddStyle('margin-right','20px');
$div->AddStyle('border','1px solid black');
$div->AddStyle('padding','10px');
$div->AddStyle('font-size','10pt');
$theme->SetContent($div);
$theme->SetLayout('Popup');
?>