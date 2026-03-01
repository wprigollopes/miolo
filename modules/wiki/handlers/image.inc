<?
// +-----------------------------------------------------------------+
// | Wiki Development Team UNIVATES - Centro Universitário           |  
// +-----------------------------------------------------------------+
// |      Copyleft (l) 2002 UNIVATES, Lajeado/RS - Brasil            |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolowiki.codigolivre.org.br                       |
// +-----------------------------------------------------------------+
// | Created: 2002/12/16 Alexandre Heitor Schmidt xandehs@univates.br|
// |                     Nasair Júnior da Silva   njunior@univates.br|
// |                                                                 |
// | History: Initial Revision                                       |
// +-----------------------------------------------------------------+
    
    $home = 'main:topic';
    
    $navbar->AddOption(_M('Image',$module),$module,"$home:image&item=$item");
    $handler = $MIOLO->GetActionURL($module,"$home:image&item=$item");
    $ui = $MIOLO->GetUI();

    $projects = $MIOLO->GetBusiness($module, 'Projects');
    
    $project = $projects->GetProject($item);
    
    $form = $ui->GetForm($module,'SendImageForm',$project);
   
    if ( $form->IsSubmitted() )
    {
        $goto = $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic");
        
        $form = $ui->GetForm($module,'TopicForm',$project);
        
        $file = $HTTP_POST_FILES['frm_image'];
        
        $MIOLO->Assert($file,_M('Missing @1',$module,'file'),$goto);
        
        $ext = substr(strrchr(strtolower($file['name']),'.'),1);
        
        $MIOLO->Assert($ext=='gif'||$ext=='png'||$ext=='jpg',_M('Image type @1 is no allowed!',$module,$ext));
        
        $sent = $projects->SendImage($item,$file['name'],$file['tmp_name']);

        if ($sent)
        {
            if ( ! headers_sent() )
            {
                header('Location: ' . $goto);
            }
            
            else
            {
                $MIOLO->Information(_M("Image @1 has been received successfully!",$module,$file['name']),$goto);
            }
        }
        else
        {
            $MIOLO->Information(_M("Your file wasn't sent. Please, try again or contact the administrator.",$module),$goto);
        }
    }
    else
    {
        $theme->SetContent($form);
    }

?>
