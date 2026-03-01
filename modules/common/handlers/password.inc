<?php
#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# @title
#   
#
# @description
#   
#
# @created
#   2003/08/12
#
# @organisation
#   MIOLO - Miolo Development Team - UNIVATES Centro Universitario
#
# @legal
#   CopyLeft (L) 2001-2002 UNIVATES, Lajeado/RS - Brasil
#   Licensed under GPL (see COPYING.TXT or FSF at www.fsf.org for
#   further details)
#
# @contributors
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
# 
# @maintainers
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
#   Thomas Spriestersbach    [author] [ts@interact2000.com.br]
#
# @history
#   See CVS history.
#
# @id $Id: password.inc,v 1.4 2003/08/29 17:35:46 vgartner Exp $
#---------------------------------------------------------------------
    
    $ui = $MIOLO->GetUI();
      
    // Was the form submitted?
    if ( Form::IsSubmitted() )
    {
        $user_id = Form::GetFormValue('uid');
        $business = $MIOLO->GetBusiness($module,'admin');
        
        if ( ( Form::GetFormValue('sendHash') == 'Yes' ) &&
             ( $user_id != '' )
            )
        {
            $user = $business->GetUser($user_id);

            if ( ! empty( $user) )
            {
                $user_name = $user->name;
                $user_mail = $user->email;
            
                $user_hash = md5($user_name);
                $confirm_hash = md5($user_hash . strval(time()) . strval(rand()));
            
                //$result = $business->UpdateUserConfirm_Hash($user_name, $confirm_hash);
                $url_confirm = $MIOLO->GetActionURL('common','password') . '&confirm_hash=' . $confirm_hash;
            
                $message = "Olá $user_name, \n".
                           "alguém (possivelmente você) solicitou a mudança de senha no sistema. \n".
                           "Por isso estamos lhe enviando este e-mail, com o endereço onde isso poderá \n".
                           "ser feito. Caso você não tenha solicitado essa alteração, simplesmente \n".
                           "ignore este e-mail e nada será alterado.\n\n".
                           "Para modificar a senha, acesse: $url_confirm";

                mail($user_mail, "Modificação de Senha",$message,"From: nao_responda@$GLOBALS[HTTP_HOST]");
                $result = $business->SetConfirm_Hash($user_id, $confirm_hash);
                
                if ( $result )
                {
                    $url = $MIOLO->GetActionURL('common','main');

                    $theme->SetContent( $MIOLO->Information('Estamos enviando um e-mail com o link para a alteração de senhas.', $url) );
            
                    return;
                }
                
                $theme->SetContent( $MIOLO->Error($business->GetErrors() , $url) );
            }
            $err = "Usuário Não Cadastrado.";
        }
        else
        {
            // Gets the passwords
            $pwd  = Form::GetFormValue('pwd');
            $pwd1 = Form::GetFormValue('pwd1');

            // Verifies if the passwords are the same
            if ( $pwd == $pwd1 )
            {
                //Change the user´s password
                $result = $business->UpdateUserPassword($user_id, $pwd);
                
                $url = $MIOLO->GetActionURL('common','login');
                
                $theme->SetContent( $MIOLO->Information('Senhas alteradas.', $url) );
                
                return;
            }
            
            $err = 'As senhas não são iguais.';
            $retype = 'not_the_same';
        }
    }  
        
    // New form
    $form = new Form( 'MIOLO LOGIN' );
    $confirm_hash = $_REQUEST['confirm_hash'];
    
    if ( $item == 'retrieve' )
    {        
        $fields = Array( new TextField('', 'sendHash', 'Yes', 0), //hidden
                         new TextField( _M('Username'),'uid',null,20),
                        );

        $form->SetTitle( _M('Forgot my Password') );
        $action = $MIOLO->GetActionURL('common','password','retrieve');
    }
    elseif ( ( isset($confirm_hash) ) || ( $retype == 'not_the_same') )
    {
        $business = $MIOLO->GetBusiness($module,'admin');
        $user = $business->GetUserIdFromHash($confirm_hash);
            
        $action = MIOLO::GetCurrentURL();
        
        if ( isset($user) )
        {
            $form->SetTitle( _M('New Password') );
            $fields = Array( new PasswordField( _M('Password'), 'pwd', $pwd, 20),
                             new PasswordField( _M('Retype Password'), 'pwd1', $pwd1, 20),
                             new TextField('','uid',$user,0),
                             new TextField('', 'confirm_hash', $confirm_hash, 0), //hidden
                            );
        }
        else
        {
            $err = $business->GetErrors();
            $theme->SetContent( $MIOLO->Error("Hash de confirmação não encontrado." . '<br>' . $err ,
                                              $action)
                               );
        }
    }
    
    //Set the form action
    $form->SetAction($action);
    //Set the fields on the form
    $form->SetFields($fields);
    
    $form->AddError($err);
    // Here we set the form on the page content
    $theme->SetContent($form);
    
    // Now it's time to generate the page
    $MIOLO->GenerateTheme();
    
?>
