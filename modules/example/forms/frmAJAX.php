<?php

class frmAJAX extends MFormAJAX
{
    /*
      ATENTION: These methods of use AJAX is deprecated. It is showed here to test compatibility.
      The new method of use Ajax can be seen at controls:events.
     */
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'AJAX'));

        // register the methods callable by CPAINT
        // method names prefixed by 'ajax' are registered automagically
        $this->registerMethod('onSelectImage');

        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        //
        // createFields is executed only if this is not a Ajax Call
        //
        // include the client (javascript) code in the page
        $url = $this->manager->getActionURL('example', 'scripts:frmAJAX.js');
        $this->page->addScriptURL($url);

        // get some data to example
        $curso = $this->manager->getBusiness('example', 'curso');
        $query = $curso->listAll();
        $arrayA = array( "1" => "opt1", "2" => "opt2", "3" => "opt3" );

        // an "ajax handler" let us to handler generics ajax calls
        // the "_" prefix escapes the handler "main"
        // and goes straight to handler "ajaxhandler" (without the "_")
        $urlAjaxHandler = $this->manager->getActionURL('example', '_ajaxhandler');

        // get the names of image files to example
        $path = $this->manager->getConf('home.modules') . '/example' . $this->manager->getConf('home.module.images') . '/';
        $files = $this->manager->listFiles($path, 'f');

        // define the fields
        // remember to allocate space (usually a MDiv) to receive the html code
        $s = new MSelection("secondSelection", "", _M('Selection', $module), array( ));
        $s->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $fields = array(
            new MLabel(_M("Dynamic options for selection: create a MSelection based on another's choice", $module), '', true),
            array(
                new MSelection("firstSelection", "", "Selection", $arrayA),
                new MButton('btnSel', '[Select]', 'ajaxSelection.call();'), // set onclick
                new MDiv('m_secondSelection', $s),
            ),
            new MSeparator(),
            new MLabel('Using ajaxhandler', '', true),
            array(
                new MSelection("selSample", "", _M('Selection', $module), $arrayA),
                // set onclick to call ajaxhandler, after change the target url
                new MButton('btnSelSample', '[ajaxhandler]', "ajaxHandlerSample.url = '$urlAjaxHandler'; ajaxHandlerSample.call();"),
                new MDiv("divSample"),
            ),
            new MSeparator(),
            new MLabel('Using response_type=xml: receives a XML code as response', '', true),
            array(
                new MSelection("selCurso", "", "Cursos", $query->chunkResult()),
                new MButton('btnCurso', '[Show alunos]', 'ajaxCursoSelection.call();'), // set onclick
                new MButton('btnCursoClear', '[Clear alunos]', "miolo.getElementById('sel3').innerHTML=''; miolo.getElementById('sel4').innerHTML='';"), // set onclick
            ),
            new MDiv('sel3', ''), // a MDiv to show the query result
            new MDiv('sel4', ''), // a MDiv to show the xml code send by cpaint
            new MSeparator(),
            new MLabel('Using response_type=JSON: receives a Javascript Object as response', '', true),
            array(
                new MSelection("selCursoJSON", "", "Cursos", $query->chunkResult()),
                new MButton('btnCursoJSON', '[Show alunos]', 'ajaxCursoSelectionJSON.call();'), // set onclick
                new MButton('btnCursoClearJSON', '[Clear alunos]', "miolo.getElementById('selJSON').innerHTML='';"), // set onclick
            ),
            new MDiv('selJSON', ''), // a MDiv to show the query result
            new MSeparator(),
            new MLabel('Using response_type=JSON: receives a PHP Object as response', '', true),
            array(
                new MSelection("selCursoPHPJSON", "", "Curso", $query->chunkResult()),
                new MButton('btnCursoPHPJSON', '[Show data]', 'ajaxCursoSelectionPHPJSON.call();'), // set onclick
                new MButton('btnCursoClearPHPJSON', '[Clear data]', "miolo.getElementById('selPHPJSON').innerHTML='';"), // set onclick
            ),
            new MDiv('selPHPJSON', ''), // a MDiv to show the query result
            new MSeparator(),
            new MLabel('Using response_type=OBJECT: receives a Javascript Object as response', '', true),
            array(
                new MSelection("selCursoObject", "", "Cursos", $query->chunkResult()),
                new MButton('btnCursoObject', '[Show alunos]', 'ajaxCursoSelectionObject.call();'), // set onclick
                new MButton('btnCursoClearObject', '[Clear alunos]', "miolo.getElementById('selObject').innerHTML='';"), // set onclick
            ),
            new MDiv('selObject', ''), // a MDiv to show the query result
            new MSeparator(),
            new MLabel('Using response_type=text: receives a HTML code as response', '', true),
            array(
                new MSelection("selImage", "", "Images", $files),
                new MDiv('sel5', ''), // a div to show the image at onchange event
            ),
            new MSeparator(),
            new MLabel('Using MButtonAjax: <u>no need to write Javascript Code</u>; response_type=text: receives a HTML code as response', '', true),
            array(
                new MSelection("selImageName", "", "Images", $files),
                // MButtonAjax - no need to javascript code
                new MButtonAjax('btnAjax', '[Show Image]', 'sel6', 'selImageName', 'ajaxBtnAjaxClick'),
                new MDiv('sel6', ''), // a div to show the image at MButtonAjax click
            ),
            new MSeparator(),
            array(
                new MButton('btnPost', '[A Event Post Button]'),
                new MTextLabel('myText', '', '&nbsp;'),
            ),
        );
        $this->setFields($fields);
        $this->selImage->addAttribute('onchange', 'ajaxImage.call();'); // set onchange
    }

    public function ajax_btnSel($args)
    {
        $module = MIOLO::getCurrentModule();
        // $args is a object with the named parameters
        $value = $args->value;
        $option = $args->option;
        // this method is called by CPAINT at btnSel onclick event
        if ( $value !== '' )
        {
            $array['1'] = array( "11", "12", "13" );
            $array['2'] = array( "21", "22", "23" );
            $array['3'] = array( "31", "32", "33" );
            // create a new MSelection with option based in another selection
            $sel2 = new MSelection("secondSelection", "", _M('Options for', $module) . " <b>$option</b>", $array[$value]);
            $sel2->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
            // response_type = TEXT : set the ajax area of theme 
            $this->manager->getTheme()->setAjaxContent($sel2);
        }
    }

    public function ajax_btnCurso($args)
    {
        // this method is called by CPAINT at btnGroup onclick event
        $idCurso = $args;
        $curso = $this->manager->getBusiness('example', 'curso', $idCurso);
        // retrieve alunos with name initial = 'E'
        $query = $curso->listAlunos('E');
        // at Javascript was defined response_type = XML or JSON
        // so, we will build a xml object to send, using CPAINT library methods
        // atribute $this->cp is the cpaint object (created by the parent)
        $result_node = $this->cp->add_node('aluno');
        $query->moveFirst();
        while ( !$query->eof )
        {
            $name_node = $result_node->add_node('nome');
            $name_node->set_data($query->fields("nome"));
            $query->moveNext();
        }
        // response_type = XML or JSON : itÂ´s not necessary to set the ajax area of theme
    }

    public function ajax_btnGetCurso($args)
    {
        // this method is called by CPAINT at btnCursoPHPJSON onclick event
        $idCurso = $args;
        $curso = $this->manager->getBusiness('example', 'curso', $idCurso);
        // at Javascript was defined response_type = JSON
        // so, we will return a PHP Object, using CPAINT library methods
        // atribute $this->cp is the cpaint object (created by the parent)
        $data = $curso->getData();
        $this->cp->set_data($data);
        // response_type = JSON : itÂ´s not necessary to set the ajax area of theme
    }

    function onSelectImage($args)
    {
        // this method is called by CPAINT at selImage onchange event
        $image = $args;
        $file = $this->manager->getActionURL('example', "images:$image");
        $sel4 = new MImage('imgAJAX', NULL, $file);
        // response_type = TEXT : set the ajax area of theme 
        $this->manager->getTheme()->setAjaxContent($sel4);
    }

    public function ajaxBtnAjaxClick($args)
    {
        // this method is called by CPAINT at btnAjax click event
        $image = $args;
        $file = $this->manager->getActionURL('example', "images:$image");
        $img = new MImage('imgAJAX', NULL, $file);
        // response_type = TEXT : set the ajax area of theme 
        $this->manager->getTheme()->setAjaxContent($img);
    }

    function btnPost_click()
    {
        $this->setFieldValue('myText', 'onClick event on btnPost');
    }

}
