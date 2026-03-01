<?php

    $MIOLO->Trace('file:'.$_SERVER['SCRIPT_FILENAME']);
    
    $theme->AddScript("common.js");

    $module = $context->module;
    $action = $context->action;
    $item   = $context->item;
    
    if ( ! $module )
    {
        $module = 'common';
    }

    $a = $context ? $context->ShiftAction() : 'main';
    
    $handled = $MIOLO->InvokeHandler($module,$a);

    if( ! $handled )
    {
        include_once($MIOLOCONF['home']['modules'] .'/menu.inc');
        
        $login = $MIOLO->GetLogin();
//$MIOLO->Dump($login);
        // Shows the Logout menu option if the user is logged in, otherwise
        // the Login menu option will be showed
        $auto = $MIOLOCONF['login']['auto'];

        if ( $login && $login->id &&
             ( ! $auto || ( $MIOLOCONF['login'][$auto]['id'] &&
                            $login->id != $MIOLOCONF['login'][$auto]['id']) ) )
        {
             $sysMenu->AddOption('Logout','common','logout');
        }
        else
        {
            $sysMenu->AddOption('Login','common','login');
        }

        if ($login)
        {
            $db = $MIOLO->GetBusiness('common');
            $user = $db->GetUser($login->id);
            $html = '<div style="width: 100%; height: 100%; text-align: center;">' .
                    '<h1>' . _M('Welcome') . '</h1>' .
                    '<a href="mailto:' . $user->email . '">' .
                    $user->name . '</a>' .
                    '<p>&nbsp;</p><img alt="" src="/images/logo_miolo.png"></div>';
        }
        else
        {
            $html = '<div style="width: 100%; height: 100%; text-align: center;">' .
                    '<h1>' . _M('Welcome') . '</h1>'.
                    '<p>&nbsp;</p><img alt="" src="/images/logo_miolo.png"></div>';
        }

        $theme->SetContent($html);
    }
    
    $MIOLO->GenerateTheme();
?>
