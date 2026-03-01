<?php
/**
 * Brief Class Description.
 * Complete Class Description.
 */
class MHandler
{
    /**
     * Attribute Description.
     */
    protected $manager;

    /**
     * Attribute Description.
     */
    protected $module;

    /**
     * Attribute Description.
     */
    protected $path;

    /**
     * Attribute Description.
     */
    protected $theme;

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $manager (tipo) desc
     * @param $module (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function __construct($manager, $module)
    {
        $this->manager = $manager;
        $this->module = $module;
        $this->path = $this->manager->getConf('home.modules') . '/' . $module . '/handlers/';
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public function init()
    {
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $handler (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function dispatch($handler)
    {
        global $context, $module, $action, $item, $session, $page, $auth, $perms, $navbar, $theme, $history, $self, $url;

        $MIOLO = $this->manager;
        $context = $this->manager->context;
        $module = $context->module;
        $action = $context->action;
        $self = $context->action;
        $item = $context->getVar('item');
        $session = $this->manager->session;
        $page = $this->manager->getPage();
        $url = $this->manager->getCurrentURL();
        $theme = $this->manager->getTheme();
        $auth = $this->manager->getAuth();
        $perms = $this->manager->getPerms();
        $navbar = $theme->getElement('navigation');
        $history = $this->manager->history;

        //redirect for institutional evaluation
        if($module == 'avinst' && $action == 'main')
        {
            if(SAGU::getParameterBoolean('BASIC', 'AVALIACAO_FORM_RESPONSIVO') == true)
            {
                $MIOLO->page->redirect($_SERVER['HTTPS_HOST']. '/core/ava');
            }
        }

        if($_SESSION['isCentral'] == true && !SAGU::isAllowedAction())
        {
            if($action != 'logout')
            {
                $allowedAction = explode(',', SAGU::getParameter('BASIC', 'TRANSACTIONS_PERMITED_WITH_DATEANDCPF_LOGIN'));

                if(strlen($action) > 0)
                {
                    $filters = new stdClass();
                    $filters->action = $action;
                    $transactions = AdmMioloTransaction::search($filters);
                }

                $block = true;
                foreach ($transactions as $key => $transaction)
                {
                    if(in_array($transaction->idTransaction, $allowedAction))
                    {
                        $block = false;
                    }
                }

                if($block == true || count($transactions) == 0)
                {
                    $MIOLO->error('Acesso negado.');
                }
            }
        }

        $this->manager->trace("Handler:dispatch: $handler");

        $file = $this->path . $handler . ($MIOLO->getConf("options.fileextension") == '2' ? '.inc' : '.php');

        if ($return = file_exists($file))
        {
            include ($file);
        }

        return $return;
    }
}
