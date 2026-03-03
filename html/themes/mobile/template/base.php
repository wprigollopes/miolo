<?php
$url = $miolo->getConf('home.url');
$action = $miolo->getPage()->action;
$id = $miolo->getPage()->name;
$lang = strtolower(str_replace('_', '-', $miolo->getConf('i18n.language')));
$charset = $miolo->getConf('options.charset');
define('TITLE', 'Miolo Mobile');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title><?php echo TITLE;?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/dojo.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/miolo.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/mobile.css" media="handheld">
        <link rel="stylesheet" href="<?php echo $url?>/scripts/jquery/jquery.mobile-1.1.0.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/jqmobilefix.css">
        
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/s_calendar.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $url?>/themes/<?php echo $theme->id?>/m_eventcalendar.css">

        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>" />
        <meta name="Generator" content="MIOLO Version Miolo 2.5; http://www.miolo.org.br">

    </head>
    <body class="mThemeBody">
        <div id="<?php echo $id?>">
            <div id="stdout" class="mStdOut"></div>

            <div id="mLoadingMessageBg"></div>
            <div id="mLoadingMessage">
                <div id="mLoadingMessageImage">
                    <div id="mLoadingMessageText">Carregando...</div>
                </div>
            </div>

            <div id="__mainForm__scripts" dojoType="dojox.layout.ContentPane" layoutAlign="client" executeScripts="true" cleanContent="true">
            </div>

             <div id="__mainPage" data-role="page" data-theme="c">
                 
                <div id="__mainForm" dojoType="dojox.layout.ContentPane" layoutAlign="client" executeScripts="false" cleanContent="false" data-role="content">
                    
                </div>
            </div>

        </div>
        
        <div id="mDialogContainer"></div>

        <script src="<?php echo $url?>/scripts/jquery/jquery-1.7.2.min.js"></script>
        <script src="<?php echo $url?>/scripts/jquery/dojo-fix.js"></script>
        <script src="<?php echo $url?>/scripts/jquery/jquery.mobile-1.1.0.min.js"></script>
        <script src="<?php echo $url?>/scripts/jquery/plugin/jquery.printElement.js"></script>
        

        <script type="text/javascript" src="<?php echo $url?>/scripts/calendar/m_eventcalendar.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/datepicker/calendar.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/datepicker/calendar-setup.js"></script>
        
        
        <script type="text/javascript" src="<?php echo $url?>/scripts/dojoroot/dojo/dojo.js" 
        data-dojo-config="usePlainJson:true, parseOnLoad:false, preventBackButtonFix:false, locale:'<?php echo $lang?>'"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_miolo.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_hash.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_page.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_ajax.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_encoding.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_box.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_form.js"></script>
        <script type="text/javascript" src="<?php echo $url?>/scripts/m_md5.js"></script>

        
        
        
        
        
        <script type="text/javascript">
            <!--
            miolo.loadDeps(false);
            miolo.configureHistory("<?php echo $action?>");
            dojo.addOnLoad(miolo.initHistory);
            //-->
        </script>
    </body>
</html>

<?php require_once './cookiesPopup.php'; ?>
