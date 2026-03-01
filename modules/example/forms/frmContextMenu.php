<?php

class frmContextMenu extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Context Menu', MIOLO::getCurrentModule()));

        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        parent::createFields();

        // Context menu createad via AJAX
        $fields['divExample1'] = new MDiv('divExample1', _M('AJAX context menu example', $module));
        $fields['divExample1']->addStyle('height', '30px');
        $fields['divExample1']->addStyle('border', '1px solid #aaa');
        $fields['divExample1']->addStyle('margin-bottom', '30px');

        // Connect the right click to an AJAX function
        MUtil::setRightClickAjaxAction($fields['divExample1'], 'createContextMenu');

        $fields[] = new MDiv('divMenu1', NULL, NULL, 'style="display:none"');


        // Context menu associated to a control
        $fields['divExample2'] = new MDiv('divExample2', _M('Context menu associated to a @1', $module, 'div'));
        $fields['divExample2']->addStyle('height', '30px');
        $fields['divExample2']->addStyle('border', '1px solid #aaa');
        $fields['divExample2']->addStyle('margin-bottom', '30px');

        $contextMenu = new MContextMenu('menuJS', MContextMenu::TYPE_JS);
        $contextMenu->addCustomItem(_M('Item @1', $module, '1'), "alert('item 1');", MContextMenu::ICON_COPY);
        $contextMenu->addCustomItem(_M('Item @1', $module, '2'), "alert('item 2');", MContextMenu::ICON_CUT);
        $contextMenu->setTarget($fields['divExample2']);
        $fields[] = $contextMenu;


        // Context menu associated to a grid
        $fields[] = new MDiv('divExample3', _M('Context menu associated to a @1', $module, 'MSpecialGrid'));

        $columns[] = new MGridColumn(_M('Column @1', $module, '1'), MGrid::ALIGN_CENTER);
        $columns[] = new MGridColumn(_M('Column @1', $module, '2'), MGrid::ALIGN_CENTER);

        $data[] = array('1:1', '1:2');
        $data[] = array('2:1', '2:2');
        $data[] = array('3:1', '3:2');

        $grid = new MSpecialGrid($data, $columns, 'gridId');
        $grid->setData($data);
        $fields[] = $grid;

        $contextMenu = new MContextMenu('menuGrid', MContextMenu::TYPE_JS);
        $contextMenu->addCustomItem(_M('Edit', $module), "alert('edit')", MContextMenu::ICON_EDIT);
        $contextMenu->addCustomItem(_M('View', $module), "alert('view')", MContextMenu::ICON_VIEW);
        $contextMenu->addCustomItem(_M('Remove', $module), "alert('remove')", MContextMenu::ICON_REMOVE);
        $contextMenu->addCustomItem(_M('Duplicate', $module), "alert('duplicate')", MContextMenu::ICON_DUPLICATE);
        $contextMenu->setTarget($grid);
        $fields[] = $contextMenu;

        $this->addFields($fields);
    }

    /**
     * AJAX method to create the context menu on the first div example.
     *
     * @param type $coords Click coordinates are received as parameter on AJAX
     * functions called by right click action.
     */
    public function createContextMenu($coords)
    {
        $module = MIOLO::getCurrentModule();

        $contextMenu2 = new MStaticMenu('subMenu', NULL, true);
        $contextMenu2->addCustomItem(_M('Copy', $module), "alert('copy')", MContextMenu::ICON_COPY);
        $contextMenu2->addCustomItem(_M('Cut', $module), "alert('cut')", MContextMenu::ICON_CUT);

        $contextMenu = new MContextMenu('menuAjax', MContextMenu::TYPE_AJAX);
        $contextMenu->addItem(new MMenuItem(_M('Insert', $module), "alert('insert')", MContextMenu::ICON_INSERT));
        $contextMenu->addCustomItem(_M('Edit', $module), "alert('edit')", MContextMenu::ICON_EDIT);
        $contextMenu->addCustomItem(_M('Remove', $module), "alert('remove')", MContextMenu::ICON_REMOVE);
        $contextMenu->addSeparator();
        $contextMenu->addSubMenu($contextMenu2, _M('Sub Menu', $module), NULL, MContextMenu::ICON_WORKFLOW);
        
        // Needed for AJAX requests. Coordinates are received as parameter.
        $contextMenu->setCoords($coords);

        $this->setResponse($contextMenu, 'divMenu1');
    }
}
