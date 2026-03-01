<?

global $MIOLOCONF;

$topics = array('db'   => $MIOLOCONF['home']['miolo'] . '/database.class',
                'ui'   => $MIOLOCONF['home']['miolo'] . '/ui.class',
                'form' => $MIOLOCONF['home']['miolo'] . '/ui/form.class');

$t = $topics[$item];

if ( ! $t )
{
    return false;
}

$url = $MIOLO->GetActionURL('common','main:doc:index:file',$t);

header('Location: '. $url);

return true;

?>
