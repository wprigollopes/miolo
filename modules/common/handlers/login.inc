<?php
    
    $MIOLO->LogMessage('[LOGIN]file:'.$SCRIPT_NAME);
    
    $MIOLO->LogMessage('[LOGIN] Using login prompt');
    
    $ui = $MIOLO->GetUI();
    
    $err = null;
    
    // Was the form submitted?
    if ( Form::IsSubmitted() )
    {
        $MIOLO->LogMessage('[LOGIN] Validating login information');
        
        // Max login tryes
        $max_tries = 3;
        
        // Gets the form data
        $uid = Form::GetFormValue('uid');
        $pwd = md5(Form::GetFormValue('pwd'));

        // Verifies if the user and password are correct.
        if ( $MIOLO->Authenticate($uid, $pwd) )
        {
            $return_to = Form::GetFormValue('return_to',$MIOLO->_Request('return_to'));
            
            if ( $return_to )
            {
                $url = $return_to;
            }
            else
            {
                $url = $MIOLO->GetActionURL('common','main');
            }
            
            header('Location:'.$url);
            exit;
        }
        
        $tries = Form::GetFormValue('tries');
        if ( $tries >= $max_tries )
        {
            $MIOLO->Error(_M('Incorrect Login!'));
        }
        
        else
        {
            $err = _M('Incorrect Login!') . ' - ' . ( $max_tries - $tries) . 
                   ' ' . _M('tries remaining.');
        }
    }
    
    $tries++;
    
    $pwd = null;
    
    $return_to = Form::GetFormValue('return_to',MIOLO::_REQUEST('return_to'));
    
    // Lets create our form fields
    $fields = Array( new TextField('', 'return_to', $return_to, 0), //hidden
                     new Text( $content ),
                     new TextField( _M('Username'),'uid',null,20),
                     new PasswordField( _M('Password'),'pwd',null,20),
                     new FormField(null,'tries', $tries) //hidden
                    );
    // New form
    $form = new Form('Login');

    //GetActionURL creates a URL link to module common and file login.inc (this file :-)
    $action = $MIOLO->GetActionURL('common','login');
    //Set the form action
    $form->SetAction($action);
    //Set the fields on the form
    $form->SetFields($fields);
    $form->SetButtonLabel(0, _M('Login') );
    
    $url_password = $MIOLO->GetActionURL('common','password','retrieve');
    $form->AddButton( _M('Forgot my Password'), 'forgot_pwd' , "GotoURL('$url_password')" );

    if ( $err )
    {
        $form->AddError($err);
    }
    
    // Here we set the form on the page content
    $theme->SetContent($form);
    
    // Now it's time to generate the page
    $MIOLO->GenerateTheme();
    
?>
