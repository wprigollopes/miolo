<?php
    if ($module == '')
    {
        $module = $MIOLO->mad;
    }

    if (!is_null($sa = $context->ShiftAction()))
    {
        $a = $sa;
    }
    elseif ($module != $MIOLO->mad)
    {
        $a = 'main';
    }
	$handled = $MIOLO->InvokeHandler($module,$a);

    if( ! $handled )
    {
        $ui = $MIOLO->GetUI();
        if ($MIOLO->GetConf('options.authmd5'))
        {
            $content = $ui->GetForm($module,'frmLoginMD5');
        }
        else
        {
            $content = $ui->GetForm($module,'frmLogin');
        }
        $theme->SetContent($content);
    }
    include_once($MIOLO->GetConf('home.modules') .'/main_menu.inc');
?>