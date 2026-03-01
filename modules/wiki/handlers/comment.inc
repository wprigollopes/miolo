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
    
    $navbar->AddOption(_M('Add a comment',$module),$module,"$home:comment");
    
    $goto = $MIOLO->GetActionURL($module,"$home&item=$item&topic=$topic");
    
    $here = "$home:comment&option=$option";
    
    $ui = $MIOLO->GetUI();

    $comments = $MIOLO->GetBusiness($module, 'Comments');
    
    if ($option == 'delete')
    {
        if ($conf == 'yes')
        {
            if ( $comments->DeleteComment($item,$topic,$comment) )
            {
                if ( ! headers_sent() )
                {
                    header('Location: ' . $goto);
                }
                else
                {
                    $MIOLO->Information(_M("The comment has been deleted successfully.",$module),$goto);
                }
            }
            
            else
            {
                $MIOLO->Error(_M("Error when trying to deleted comment.",$module),$goto);
            }
        }
        
        elseif ($conf == 'no')
        {
            if ( ! headers_sent() )
            {
                header('Location: ' . $goto);
            }
            else
            {
                $MIOLO->Information(_M("Your comment was not deleted.",$module),$goto);
            }
        }
        
        else
        {
            $MIOLO->Confirmation(_M("Your will delete this comment. Are you sure ?",$module),$here.'&conf=yes',$here.'&conf=no');
        }
    }
    
    else if ($option == 'hide')
    {
        $comments->AproveComment($project,$topic,$comment,'2');
        $MIOLO->Information(_M("The comment is visible only to administrators logins.",$module),$goto);
    }
    
    else if ($option == 'aprove' || $option == 'show')
    {
        $comments->AproveComment($project,$topic,$comment,'1');
        $MIOLO->Information(_M("The comment is visible to all the access.",$module),$goto);
    }
    
    else
    {
        $data = new WikiTopic();
        $data->topic_id = $topic;
        $data->project_id = $project;

        $form = $ui->GetForm($module,'CommentForm',$data);
        
        if ( $form->IsSubmitted() )
        {
            $data = $form->GetData();
            $comments->AddComment($data);
            $bus_set = $MIOLO->GetBusiness($module,'Settings');
            $settings = $bus_set->GetSettings();
            
            if ($settings->need_comment_aproval === true)
            {
                $text = _M("Your comment was added with success, but it requires the administrator aproval!",$module);
            }
            else
            {
                $text = _M("Your comment was added with success!",$module);
            }
            
            $content = $MIOLO->Information($text,$goto);
        }
        
        $theme->SetContent($form);
    }
?>
