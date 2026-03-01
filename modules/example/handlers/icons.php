<?
    $ui = $MIOLO->getUI();

    $navbar->addOption('Icons', $module, $action);
    $theme->clearContent();
    $close = $MIOLO->getActionURL($module, 'main');

    $subdir = array('general','buttons','xp16','xp32','tb','default','gnome','kde3');
    foreach($subdir as $i=>$s)
    {
       $panel[$i] = new MPanel('pnlIcons'.$i, 'Icons::'.$s, '', $close);
       $dir = $MIOLO->getConf('home.images') . '/icons/' . $s;
       $files = $MIOLO->listFiles($dir,'a');
       asort($files);
       foreach($files as $f)
       {
           $location = $MIOLO->getConf('home.url') . '/images/icons/'.$s.'/'.$f;
           $panel[$i]->addControl(new MImage($f, $f, $location));
       }
    }

    $theme->appendContent($panel);
