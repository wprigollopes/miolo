<?
    $navbar->AddOption('Checkbox Group',$module,'main:checkboxes');
    
    $ui = $MIOLO->GetUI();
    
    $form = new Form("Checkbox Test");

    $form->AddField( new CheckBoxGroup('Operador','oper_cb',array('AND','OR','NOT')) );
    $form->AddField( new RadioButtonGroup('Operador','oper_rb',array('AND','OR','NOT')) );
    $form->AddField( new Separator('Check Boxes') );
    $form->AddField( new TextLabel('Checked', Form::GetFormValue('oper_cb_0') . ' ' . Form::GetFormValue('oper_cb_1') . ' ' .  Form::GetFormValue('oper_cb_2')) );
    $form->AddField( new Separator('Radio Buttons') );
    $form->AddField( new TextLabel( 'Checked', Form::GetFormValue('oper_rb') ) );
    
    $theme->SetContent( $form );
?>
