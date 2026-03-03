<?php

class frmStaticMenu extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        $title = _M('Static Menu', $module);

        parent::__construct($title);
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        parent::createFields();

        $itens[] = new MMenuItem(_M('Item @1', $module, '1'), "dojo.byId('responseDiv').innerHTML = 'item 1';", MStaticMenu::ICON_COPY);

        $subMenu = new MStaticMenu('subMenu', $itens, true);
        $subMenu->addCustomItem(_M('Item @1', $module, '2'), "dojo.byId('responseDiv').innerHTML = 'item 2';", MStaticMenu::ICON_CUT);

        $menu = new MStaticMenu('staticMenu');
        $menu->addCustomItem(_M('Insert', $module), "dojo.byId('responseDiv').innerHTML = 'insert';", MStaticMenu::ICON_INSERT);
        $menu->addCustomItem(_M('Edit', $module), "dojo.byId('responseDiv').innerHTML = 'edit';", MStaticMenu::ICON_EDIT);
        $menu->addCustomItem(_M('View', $module), "dojo.byId('responseDiv').innerHTML = 'view';", MStaticMenu::ICON_VIEW);
        $menu->addCustomItem(_M('Remove', $module), "dojo.byId('responseDiv').innerHTML = 'remove';", MStaticMenu::ICON_REMOVE);
        $menu->addCustomItem(_M('Duplicate', $module), "dojo.byId('responseDiv').innerHTML = 'duplicate';", MStaticMenu::ICON_DUPLICATE);
        $menu->addSeparator();
        $menu->addSubMenu($subMenu, _M('Sub Menu', $module), "dojo.byId('responseDiv').innerHTML = 'submenu';", MStaticMenu::ICON_RELATION);
        $menu->addCustomCSS(NULL, NULL);
        $fields[] = new MBaseGroup('', _M('Static Menu Title', $module), array( $menu ));

        // A div to show the results of clicking the menu items
        $fields[] = new MDiv('responseDiv', NULL);

        $this->addFields($fields);
    }
}

?>