<?
    $theme->clearContent();
    $navbar->addOption('Insert', 'example', $self);
    // adding some information to the content area of theme
    $theme->appendContent(new MLabel("I'm at insert1 handler. The content of theme was cleared."));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Context::module : " . $context->module));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Context::action : " . $context->action));
    $theme->appendContent(new MSpacer());
    // adding a div to be accessed by the insert2 handler
    $label = new MLabel("Adding a piece of text at insert1 handler in this div");
    $label->color = 'blue';
    $div = new MDiv("insertSample",$label);
    $div->backgroundColor = "#DDD";
    $theme->appendContent($div);

    // "inserting" a other handler
    $inc = $MIOLO->getActionURL('example','insert2','a value');
    $page->insert($inc);

    // adding more content to the theme
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("I'm at insert1 handler AGAIN."));
?>
