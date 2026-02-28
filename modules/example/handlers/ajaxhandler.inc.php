<?
MIOLO::import('extensions::cpaint2.inc.php','cpaint'); 
$cp = new cpaint();
$page->addScript('x/x_core.js');
$page->addScript('x/x_dom.js');
$page->addScript('cpaint/cpaint2.inc.js');
$page->addScript('m_ajax.js');
$cp->register('ajax_sample');
if (($page->request('cpaint_function')) != "") 
{
    $theme->clearContent();
    $page->generateMethod = 'generateAJAX';
    $page->cpaint = $cp;
    $cp->start('ISO-8859-1');
}

function ajax_sample($arg)
{
    global $theme; 
    $sample = new MRawText("<br>i've received the argument: $arg");
    $theme->setAjaxContent($sample);
}
?>