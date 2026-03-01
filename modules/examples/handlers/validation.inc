<?

$MIOLO->GetUI();

$form = new Form('Validation Test');

// Validator parameters: 
// - optional: default
// - required: always must inform a valid value
// - ignore: no validation, only mask
$form->AddField( new TextField('CEP',   'cep',   null, 10, 'Código de endereçamento postal', Validator::CEPValidator()) );
$form->AddField( new TextField('Address', 'adr', 'Av. Rio das Pedras', 40, 'Read only field', Validator::MASKValidator('','readonly')) );
$form->AddField( new TextField('Phone', 'phone', null, 20, 'Phone number', Validator::PHONEValidator()) );
$form->AddField( new TextField('E-mail', 'email',null, 20, 'E-Mail address', Validator::EMAILValidator()) );
$form->AddField( new TextField('Mask Phone',   'm_phone',  null,  20, 'Mask only test', Validator::MASKValidator('(0xx99)9999-9999','optional')) );
$form->AddField( new TextField('Time',  'time',  null, 10, 'Time: HH:MM', Validator::TIMEValidator()) );
$form->AddField( new TextField('Date',  'date',  null, 10, 'Date: dd/mm/yyyy', Validator::DATEDMYValidator()) );
$form->AddField( new TextField('CNPJ',  'cnpj',  null, 20, 'Número CNPJ', Validator::CNPJValidator()) );
$form->AddField( new TextField('CPF',   'cpf',   null, 20, 'Número CPF', Validator::CPFValidator('required')) );
$form->AddField( new TextField('Mask CPF',   'm_cpf',   null, 20, 'No validation, only masking', Validator::CPFValidator('ignore')) );

$act = $MIOLO->GetActionURL($module, $action);
$form->SetAction($act);

$theme->AppendContent($form);

?>
