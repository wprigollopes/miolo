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

    $menu->AddOption(_M('Cover Page',$module),$module,"main:topic&item=$item");
    $menu->AddOption(_M('Topic Index',$module),$module,"main:topic&item=$item&option=index"); 
    $menu->AddOption(_M('Create Topic',$module),$module,"main:topic&item=$item&option=create"); 
    $menu->AddOption(_M('Show Page',$module),$module,"main:topic&item=$item&topic=$topic"); 
    $menu->AddOption(_M('Edit Page',$module),$module,"main:topic&item=$item&topic=$topic&option=edit"); 
    $menu->AddSeparator();
    $menu->AddOption(_M('Add Comment',$module),$module,"main:topic:comment&item=$item&project=$item&topic=$topic");
    
    if ( ! $MIOLO->InvokeHandler($module,$context->ShiftAction()) )
    {
        $MIOLO->Assert($item,_M('Missing project ID!'),$MIOLO->GetActionURL($module,'main'));
        
        $topic = ! $topic ? 'main' : substr($topic,0,20);
        
        $navbar->AddOption(_M('Open',$module),$module,"$home&item=$item&topic=$topic");
                           
        $ui = $MIOLO->GetUI();
    
        if ( $option == 'edit' || $option == 'create' )
        {
            $topics = $MIOLO->GetBusiness($module, 'Topics');
            
            $form = $ui->GetForm($module,'TopicForm');
            
            if ( $form->IsSubmitted() )
            {
                $data = $form->GetData();

                if ($data)
                {
                    $topic = $data->topic_id;
                    
                    if ( $topics->IsExistingTopic($data->project_id,$data->topic_id) )
                    {
                        if ( $topics->UpdateTopic($data) )
                        {
                            if ( ! headers_sent() )
                            {
                                header("Location: " . $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=view"));
                            }
                            else
                            {
                                $MIOLO->Information(_M("Topic has been sucessfully updated.",$module),
                                                    $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=view"));
                            }
                        }
                        else
                        {   
                            $MIOLO->Error(_M("Topic could not be updated!",$module),
                                          $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=edit"));
                        }
                    }
                    
                    else
                    {
                        if ( $topics->AddTopic($data) )
                        {
                            if ( ! headers_sent() )
                            {
                                header("Location: " . $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=view"));
                            }
                            else
                            {
                                $MIOLO->Information(_M("Topic has been sucessfully added.",$module),
                                                    $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=view"));
                            }
                        }
                        else
                        {   
                            $MIOLO->Error(_M("Topic could not be added!",$module),
                                          $MIOLO->GetActionURL($module,"main:topic&item=$item&topic=$topic&option=edit"));
                        }
                    }
                }
            }
            
            else
            {
                if ( $option == 'create' )
                {
                    $data = new WikiTopic();
                    $data->project_id = $item;
                }
                
                else
                {
                    $data = $topics->GetTopic($item,$topic);
    
                    if ( $data == null )
                    {
                        $data = new WikiTopic();
                        $data->project_id = $item;
                        $data->topic_id = $topic;
                        $data->title = $topic;
                    }
                }
                
                $form->SetData($data);
                
                $menu->AddSeparator();
                $menu->AddLink(_M('Formatting Tips',$module),'javascript:WIKI_showTip(tip_format)');
                
                $tip = "Emphasis: '' for italics, ''' for bold, ''''' for both. ' is a simple quote.<br><br>\\n" .
                       "Lists: tab* for bullet lists, tab# for numbered lists, tabTerm:tab for definition lists<br><br>\\n" .
                       "Links: [title|link] or [link]<br><br>\\n" .
                       "Misc: '!', '!!', '!!!' make headings, '- - - -' makes a horizontal rule, escape '[' with '[['<br><br>\\n" .
                       "To add a link, use [title|link], where link should be the topic identification. You can use as [wiki|http://wiki.codigolivre.org.br] to link to a external page (need the protocol(http://)).<br><br>\\n" .
                       "To add images, use [image:text|path], where path is the name of the image sent (only the name with extension). [image:Teste|teste.gif]. To send images, clik on the image link contained in the page text.\\n";
        
                $content = array($form,"<script language=\"JavaScript\">var tip_format = \"$tip\";</script>");
                
                $theme->AddScript('../modules/wiki/wiki.js');
                $theme->SetContent($content);
            }
        }
        
        else if ( $option == 'index' )
        {
            $topics = $MIOLO->GetBusiness($module, 'Topics');
        
            $db = $topics->GetDatabase();
            $rs = $db->Query($db->Prepare("select topic_id, code, title, \"user\", last_update from wik_topics where project_id=? order by 2,3",$item));
            $listing = new Listing();
            $listing->SetTitle(_M('Topic Index',$module));
            $listing->SetLabels(array(null,_M('Number',$module),_M('Topic',$module),_M('Author',$module),_M('Modified',$module)));
            $listing->SetData($rs);
            // $listing->AddActionText('','Show',$MIOLO->GetActionURL($module,"main:topic&item=$item&topic=%0%"));
            $theme->SetContent($listing);
            
            class WikiDateRenderer extends CellRenderer
            {
                function Generate(&$listing,$row,$column,$value)
                {   global $MIOLO;
                    $y = substr($value,0,4);
                    $m = substr($value,5,2);
                    $d = substr($value,8,2);
                    $H = substr($value,11,2);
                    $M = substr($value,14,2);
                    $S = substr($value,17,2);
                    echo "&nbsp;$d/$m/$y&nbsp;($H:$M:$S)&nbsp;";
                }
            }
            
            $listing->SetCellRenderer(2,new ActionRenderer(new TextAction('%0%',$MIOLO->GetActionURL($module,"main:topic&item=$item&topic=%0%"))));
            $listing->SetCellRenderer(3,new ActionRenderer(new TextAction('%3%',$MIOLO->GetActionURL($module,"main:topic&item=$item&topic=%3%"))));
            
            // $listing->SetCellRenderer(2,new ActionRenderer(new TextAction('%0%',$MIOLO->GetActionURL($module,"main:topic&item=$item&topic=%0%"))));
            $listing->SetCellRenderer(4,new WikiDateRenderer());
        }
        
        else
        {
            $permissions = $MIOLO->GetBusiness($module, 'Permissions');
            $permissions->CheckAccess($item, 'read', null, true);
            
            $topics = $MIOLO->GetBusiness($module, 'Topics');
        
            $data = $topics->GetTopic($item,$topic);
                
            $MIOLO->Uses('forms/WikiPage.class',$module);
            
            $page = new WikiPage();
            $page->SetTopic($data);
            
            $comments = $MIOLO->GetBusiness($module, 'Comments');
            
            $page->SetComments( $comments->GetComments($item, $topic) );
            
            $theme->SetContent($page);
        }
    }
?>
