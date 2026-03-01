<?php

class frmMenuForm extends MForm
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();

        $menu = new MMenu(_M('Example', $module));

        $href = $MIOLO->getActionURL('example', 'main:menus:menu', 'link');
        $hyperlink = new MLink('hlink', 'Hyperlink', $href);
        $linkbutton = new MLink('linkButton', _M('Link button', $module), $href);

        $menu->addOption(_M('Option @1', $module, '1'), 'example', 'main:menus:menu', '1');
        $menu->addOption(_M('Option @1', $module, '2'), 'example', 'main:menus:menu', '2');
        $menu->addOption(_M('Option @1', $module, '3'), 'example', 'main:menus:menu', '3');
        $menu->addOption(_M('Option @1', $module, '4'), 'example', 'main:menus:menu', '4');
        $menu->addSeparator();

        $menu->addLink('Link', $href);
        $menu->addHyperLink($hyperlink);
        $menu->addLinkButton($linkbutton);
        $menu->addSeparator();

        $menu->addText(_M('A small text', $module));
        $menu->addSeparator();

        $subMenu = new MMenu(_M('Submenu example', $module));
        $subMenu->addOption(_M('Suboption @1', $module, '1'), 'example', 'main:menus:menu', '1');
        $subMenu->addOption(_M('Suboption @1', $module, '2'), 'example', 'main:menus:menu', '2');
        $menu->addMenu($subMenu);

        $div = new MDiv('m-container-menu', $menu);
        parent::__construct(_M('Menu Sample', $module));

        $fields[] = $div;
        $this->setFields($fields);

        $buttons[] = new MButton('btnPost', _M('Send'));
        $this->setButtons($buttons);

        if ( $page->isPostBack() )
        {
            $this->eventHandler();
        }
    }
}
