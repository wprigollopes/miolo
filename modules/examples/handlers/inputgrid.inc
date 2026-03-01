<?
    $navbar->AddOption('Input Grid',$module,'main:inputgrid');
    
    $ui = $MIOLO->GetUI();
    
    $grid = new InputGrid('&nbsp;','cd',10);
    $grid->AddColumn('Faixa','faixa',20);
    $grid->AddColumn('Duração','tempo',5);
    
    $form = new Form("InputGrid Test");
    $form->AddField($grid);

    $theme->SetContent($form);
    
    if ( Form::IsSubmitted() )
    {
        // var_dump($HTTP_POST_VARS);
        for ( $i=0; $i<10; $i++ )
        {
            /*
            $faixa = InputGrid::GetGridValue('cd','faixa',$i);
            $tempo = InputGrid::GetGridValue('cd','tempo',$i);
            */
            
            $faixa = $grid->GetValue('faixa',$i);
            $tempo = $grid->GetValue('tempo',$i);
            
            if ( $faixa )
            {
                echo ($i + 1) . ") $faixa ($tempo)<br>\n";
            }
        }
    }

?>
