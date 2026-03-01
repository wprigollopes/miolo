<?
    // locating and changing the div created at insert1 handler
    $div = $theme->getElementById('content','insertSample');
    $label = new MLabel("<br>Adding a more text at insert2 handler in the same div");
    $label->color = 'red';
    $div->setInner(array($div->getInner(), $label));

    // adding some content to theme
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("==="));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Now, i'm at insert2 handler."));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Context::module : " . $context->module));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Context::action : " . $context->action));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("Context::item : " . $context->item));
    $theme->appendContent(new MSpacer());
    $theme->appendContent(new MLabel("==="));
