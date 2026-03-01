<?
// +-----------------------------------------------------------------+
// | MIOLO - Miolo Development Team - UNIVATES Centro Universitario  |
// +-----------------------------------------------------------------+
// | CopyLeft (L) 2001,2002 UNIVATES, Lajeado/RS - Brasil            |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigolivre.org.br                           |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// |                                                                 |
// | Created: Vilson Cristiano Gartner, Thomas Spriestersbach        |
// |                                                                 |
// +-----------------------------------------------------------------+
// $Id: main.inc,v 1.3 2004/08/23 18:29:44 vgartner Exp $

    // adicionando link na barra de navega??o, que aponte para o arquivo atual
    // a vari?vel $module vem atrav?s da URL e aponta para o m?dulo atual (tutorial)
    $navbar->AddOption('Tutorial', $module, 'main');
    // a ultima op??o da barra de navega??o n?o ? clic?vel, pois aponta para
    // a p?gina atualmente aberta. Nas p?ginas seguintes ser? clic?vel.

    
    // definindo t?tulo de menu. O objeto $menu foi criado
    // anteriormente (automaticamente) pelo pr?prio MIOLO.
    $menu->SetTitle("MIOLO Tutorial");

    // definir um conte?do
    $content = '<h1> Programando com o MIOLO </h1>';
	
    // colocando conte?do p?gina
    $theme->SetContent($content);

    // InvokeHandler: chama/passa o controle para o pr?ximo handler
    // O ShiftAction retorna o pr?ximo handler do $context.
    // O $context, consiste na string que identifica o 'action' que cont?m a ordem
    // dos arquivos que ser?o abertos. Esses arquivos (que s?o na realidade os .inc
    // sem a estens?o) devem ser separados por ':'
    // Caso arquivo n?o exista ou n?o tenha sido passado nenhum handler a fun??o 
    // retorna false. E no caso particular do teste condicional abaixo, adiciona as
    // op??ees ao menu
    if ( ! $MIOLO->InvokeHandler($module, $context->ShiftAction()) )
    {
        // Adicionar op??es ao menu
        //                 Caption do Link      module     action(nome e ordem dos arquivos)
        $menu->AddOption('Cadastrar Cidades', 'tutorial', 'main:city_insert');
        $menu->Addoption('Listar Cidades'   , 'tutorial', 'main:city_list');
        $menu->AddSeparator(); 
        $menu->AddOption('Cadastrar Pessoas', 'tutorial', 'main:person_insert');
        $menu->AddOption('Listar Pessoas'   , 'tutorial', 'main:person_list');
        $menu->AddSeparator('-');
        $menu->AddOption('Exemplo de TabbedForm'  , 'tutorial', 'main:tabbed');
        $menu->AddOption('Exemplo de LookupField'  , 'tutorial', 'main:lookup');

        // Se vc. quiser deixar o menu sempre vis?vel para todas as pr?ximas p?ginas,
        // basta tirar o teste condicional 'if' e sempre chamar o InvokeHandler ap?s 
        // adicionar as op??es no menu
    }

?>
