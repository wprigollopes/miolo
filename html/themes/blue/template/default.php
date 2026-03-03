<div id="<?php echo $form ?>_container" class="mThemeContainer">
    <div id="<?php echo $form ?>_container_top" class="mThemeContainerTop">
        <div class="banner"></div>
    </div>
<?php
// menus
if ( $miolo->getConf('options.mainmenu') == 2 )
{
    $idModule = 'mThemeContainerModule';
    $idMenu = 'mThemeContainerDhtmlMenu';
}
else if ( $miolo->getConf('options.mainmenu') == 3 )
{
    $idModule = 'mThemeContainerModule2';
    $needTable = true;
}
else
{
    $idMenu = 'mThemeContainerMenu';
}
$theme->setElementId('menus', $idMenu);

if ($theme->hasMenuOptions())
{
    if ($needTable == true)
    {
?>
      <div id="mThemeMenus">
      <div id="mThemeMenuBox">
         <div id="<?php echo $form ?>_menu">
        <table collspacing=0 cellpadding=0 cellspacing=1 border=0>
            <tr>
<?php
    }
    echo $theme->generateElement('menus');
    if ($needTable == true)
    {
?>
            </tr>
        </table>
        </div>
     </div>
    </div>

<?php
    }
}
if ($theme->getElement('module') != NULL)
{
    echo $theme->generateElement('module');
}
?>
    <div id="<?php echo $form ?>_navbar">
<?php
if ($theme->getElement('navigation')->hasOptions())
{
    echo $theme->generateElement('navigation');
}
?>
    </div>
<?php
?>
    <div id="extContent" class="mThemeContainerExt">
        <div id="<?php echo $form ?>_content" dojoType="dojox.layout.ContentPane" layoutAlign="client" executeScripts="true" cleanContent="true">
<?php
    $classContent = ( ($miolo->getConf('options.mainmenu') == 2) || ($miolo->getConf('options.mainmenu') == 3) )   ? 'mThemeContainerContentFullAjax' : 'mThemeContainerContent';
    $theme->setElementClass('content', $classContent);
    echo $theme->generateElement('content');
?>
        </div>
    </div>

    <div id="<?php echo $form ?>_bottom">
<?php
    $theme->setElement('bottom', new MStatusBar());
    $theme->setElementClass('bottom', 'mThemeContainerBottom');
    echo $theme->generateElement('bottom');
?>
    </div>
    <div id="<?php echo $form ?>_container_minbar" class="mThemeContainerMinbar">
        <div></div>
    </div>
</div>
