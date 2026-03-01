<?

$MIOLO->GetUI();

$form1 = new Form('Lookup User Test');

$form1->AddField(new LookupField('Login', // Field Label
                                'login',  // Field name
                                array('name','email'), // Fields to be completed
                                "$module", // Module
                                'User',   // Lookup/Autocomplete Name
                                '',       // Default value
                                'Exemplo Lookup User')); // Hint
$form1->AddField(new TextField('Nome','name',null,40,null, Validator::MASKValidator('','readonly')));
$form1->AddField(new TextField('EMail','email',null,40,null, Validator::MASKValidator('','readonly')));

$theme->AppendContent($form1);

?>
