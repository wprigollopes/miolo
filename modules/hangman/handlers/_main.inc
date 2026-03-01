<?
$label = 'Hello World!';
$button = new FormButton('btnHello', $label);
$button->attachEventHandler('click','OnClickButton');
$theme->SetContent($button);
$theme->EventHandler();

function OnClickButton($sender)
{
   $sender->SetLabel('Event!');
}
?>