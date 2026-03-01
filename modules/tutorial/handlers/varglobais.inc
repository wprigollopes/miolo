<?
    $ui = $MIOLO->GetUI();

    $navbar->AddOption('Variáveis Globais', $module, $self);
    $theme->ClearContent();
    $close = $MIOLO->GetActionURL($module, 'main');
    $panel = new Panel('pnlVarGlobais', 'Variáveis globais acessíveis pelos handlers:', '', $close);
    $controls = array
        (
        new MLabel('$MIOLO: acesso a instancia da classe principal do framework'),
        new MHr,
        new MLabel('$page: acesso ao objeto MPage'),
        new MHr,
        new MLabel('$context: acesso ao objeto MContext'),
        new MHr,
        new MLabel('$theme: acesso ao objeto Theme'),
        new MHr,
        new MLabel('$auth: acesso ao objeto MAuth'),
        new MHr,
        new MLabel('$perms: acesso ao objeto MPerms'),
        new MHr,
        new MLabel('$session: acesso ao objeto MSession (sessão atual)'),
        new MHr,
        new MLabel('$state: acesso ao objeto MState (variáveis de estado)'),
        new MHr,
        new MLabel('$menu: acesso ao objeto Menu (menu esquerdo)'),
        new MHr,
        new MLabel('$log: acesso ao objeto MLog (módulo admin)'),
        new MHr,
        new MLabel('$navbar: acesso ao objeto navbar (barra de navegacao)'),
        new MHr,
        new MLabel('$module: nome do módulo do handler em execução - ' . $module),
        new MHr,
        new MLabel('$action: path do handler em execução - ' . $action),
        new MHr,
        new MLabel('$item: campo item da url atual - ' . $item),
        new MHr,
        new MLabel('$self (deprecated): path do handler em execução - ' . $self),
        new MHr,
        new MLabel('$url: url completa do handler em execução - ' . $url),
        new MHr
        );

    $panel->AddControl($controls, '', 'clear');
    $theme->AppendContent($panel);
?>
