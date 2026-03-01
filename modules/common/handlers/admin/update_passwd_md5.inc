<?
    #
    # Este script encripta as senhas do arquivo cmn_users utilizando md5 
    # EXECUTE APENAS UMA VEZ !!!
    #--------------------------------------------------------------------
    # This scripts encripts the passwords in cmn_users with md5
    # RUN THIS SCRIPT ONLY ONCE !!!
    #
    # Load the follow address in the browser:
    # http://localhost/handler.php?module=common&action=admin/update_passwd_md5
    #
    # Created: 2003-12-08
    # Vilson Cristiano Gartner <vgartner@univates.br>
    # 

    $db = $MIOLO->GetDatabase('bis');

    $sql_indices = ' select login, password '.
                   '   from cmn_users ';
    $result = $db->Query( $sql_indices );
    
    $content = '<big>Atualizando senhas... / Updating passwords...</big><br>';

    foreach ( $result as $r )
    {
        $new_Password = md5($r[1]);
        
        $sql = ' update cmn_users '.
               '    set password = ? '.
               '  where login = ? ';
    
        $result = $db->Query( $db->Prepare($sql, array($new_Password, $r[0])) );
        
        $content .= "User: $r[0], password (md5): $new_Password <br>";
    }
    
    $content .= '<br><b>Concluído/Finished.</b>';
    
    $db->close();
    
    $theme->SetContent( $content );
?>