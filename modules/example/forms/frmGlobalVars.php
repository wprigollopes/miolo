<?php

class frmGlobalVars extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Global Variables', MIOLO::getCurrentModule()));
        // creates a link to view the source
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        global $module, $item, $action, $self, $url;

        $vars[] = array(
            new MLabel('$MIOLO', '', true),
            new MLabel(_M('The main class instance of the framework', $module))
        );
        $vars[] = array(
            new MLabel('$page', '', true),
            new MLabel(_M('MPage object', $module))
        );
        $vars[] = array(
            new MLabel('$context', '', true),
            new MLabel(_M('MContext object', $module))
        );
        $vars[] = array(
            new MLabel('$theme', '', true),
            new MLabel(_M('Theme object', $module))
        );
        $vars[] = array(
            new MLabel('$auth', '', true),
            new MLabel(_M('MAuth object', $module))
        );
        $vars[] = array(
            new MLabel('$perms', '', true),
            new MLabel(_M('MPerms object', $module))
        );
        $vars[] = array(
            new MLabel('$session', '', true),
            new MLabel(_M('MSession object (current session)', $module))
        );
        $vars[] = array(
            new MLabel('$state', '', true),
            new MLabel(_M('MState object (state vars)', $module))
        );
        $vars[] = array(
            new MLabel('$menu', '', true),
            new MLabel(_M('Menu object (left menu)', $module))
        );
        $vars[] = array(
            new MLabel('$log', '', true),
            new MLabel(_M('MLog object (admin module)', $module))
        );
        $vars[] = array(
            new MLabel('$navbar', '', true),
            new MLabel(_M('Navbar object (navigation bar)', $module))
        );
        $vars[] = array(
            new MLabel('$module', '', true),
            new MLabel(_M('Current module name', $module) . " - $module")
        );
        $vars[] = array(
            new MLabel('$action', '', true),
            new MLabel(_M('Current handler URL path', $module) . " - $action")
        );
        $vars[] = array(
            new MLabel('$item', '', true),
            new MLabel(_M('Item field of the current URL', $module) . " - $item")
        );
        $vars[] = array(
            new MLabel('$self (deprecated)', '', true),
            new MLabel(_M('Current handler URL path', $module) . " - $self")
        );
        $vars[] = array(
            new MLabel('$url', '', true),
            new MLabel(_M('Complete URL of the current handler', $module) . " - $url")
        );

        $colTitles[] = _M('Variable', $module);
        $colTitles[] = _M('Description', $module);

        $table = new MTableRaw(_M('Global variables accessed by handlers', $module), $vars, $colTitles);
        $table->addStyle('width', '100%');

        $this->addFields(array($table));
        $this->setButtons(new MBackButton());
    }
}
