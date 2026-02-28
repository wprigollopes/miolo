<?php

class MModularCalendar extends MDiv
{

    /**
     * @var array
     */
    private $events;

    /**
     * MModularCalendar constructor.
     * @param string $name
     * @param array $events
     */
    public function __construct($name, $events = null)
    {
        $this->events = $events;

        parent::__construct($name, array(new MDiv('wrap', new MDiv('calendar'))));

        //Realizado import com echo pois addStyle não funcionou
        echo '<link rel="stylesheet" type="text/css" href="'.MIOLO::getInstance()->getAbsoluteURL('themes/portal/modularcalendar/fullcalendar.min.css').'">';
        $this->page->addScript('modularcalendar/fullcalendar.min.js', 'portal');
    }

    public function generate()
    {

        $events = $this->events;

        /**
         * Estrutura do envento
         * {
         *     title: 'titulo',
         *     start: new Date(y, m, d, 12, 0),
         *     end: new Date(y, m, d, 14, 0),
         *     allDay: false
         * }
         *
         * Obs.: A classe Date é uma classe do JavaScript,
         *       mais informações na documentação oficial
         */

        $jsCode = "
        $(document).ready(function() {
            setTimeout(function(){
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    timeFormat: 'H(:mm)',
                    editable: true,
                    events: [{$events}]
                });
            }, 1000);
        });";

        $this->page->addJsCode($jsCode);

        return parent::generate();
    }
}




?>