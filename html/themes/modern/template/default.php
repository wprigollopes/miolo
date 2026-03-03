<div id="<?php echo $form ?>_container" class="mThemeContainer">

    <div id="<?php echo $form ?>_container_top" class="mThemeContainerTop">
        <div class="banner" onclick="javascript:miolo.doLink('/index.php', '__mainForm'); return false;"></div>
    </div>

    <div id="<?php echo $form ?>_navbar">
        <?php echo $theme->getElement('navigation')->hasOptions() ? $theme->generateElement('navigation') : '' ?>
    </div>

    <div id="extContent" class="mThemeContainerExt">
        <div id="<?php echo $form ?>_content" dojoType="dojox.layout.ContentPane" layoutAlign="client" executeScripts="true" cleanContent="true">
            <?php echo $theme->generateElement('content') ?>
        </div>
    </div>

    <div id="<?php echo $form ?>_bottom">
        <?php echo $theme->generateElement('bottom') ?>
    </div>
</div>
