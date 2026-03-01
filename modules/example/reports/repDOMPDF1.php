<?
    class repDOMPDF1 extends MDOMPDFReport
    {
        public function createFields()
        {
            $fields = array(
               new MLink('',"http://www.digitaljunkies.ca/dompdf/"),
               new MLabel("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec at odio vitae libero tempus convallis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum purus mauris, dapibus eu, sagittis quis, sagittis quis, mi. Morbi fringilla massa quis velit. Curabitur metus massa, semper mollis, molestie vel, adipiscing nec, massa. Phasellus vitae felis sed lectus dapibus facilisis. In ultrices sagittis ipsum. In at est. Integer iaculis turpis vel magna. Cras eu est. Integer porttitor ligula a tellus. Curabitur accumsan ipsum a velit. Sed laoreet lectus quis leo. Nulla pellentesque molestie ante. Quisque vestibulum est id justo."),
               new MLabel("Curabitur ut diam eu dui vestibulum pharetra. Nam pellentesque, justo non hendrerit venenatis, mi orci pretium mi, et vehicula leo arcu quis diam. Nullam mattis laoreet quam. Morbi mollis sem ut tellus. Nam mi massa, lobortis eu, sollicitudin et, iaculis et, massa. Maecenas purus mauris, luctus sit amet, pharetra in, facilisis sit amet, elit. Nullam vel erat tempus purus molestie suscipit. Vestibulum odio lorem, sollicitudin non, volutpat sit amet, tincidunt vel, nunc. Nulla quis ante vestibulum odio feugiat facilisis. Proin lorem nisl, viverra at, rhoncus quis, semper nec, mi. Donec euismod enim vitae velit. Nulla sed lectus. Vivamus placerat, lacus sed vehicula sagittis, arcu massa adipiscing lorem, bibendum luctus nisl tortor vitae leo.")
            );
            $this->setFields($fields);
        }
    }
