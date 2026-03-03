<?
    class repDOMPDF2 extends MDOMPDFReport
    {
        public function createFields()
        {
            // array com os nomes das fontes e arquivos correspondentes
            $fonts = array(
               'arial.afm'=>'arial',
               'vera.afm'=>'BitStream Vera Sans',
               'veramono.afm'=>'BitStream Vera Sans Mono',
               'verase.afm'=>'BitStream Vera Serif',
               'Courier.afm' => 'Courier',
               'Helvetica.afm' => 'Helvetica',
               'monofont.afm' => 'MonoFont',
               'tahoma.afm' => 'Tahoma',
               'Times.afm' => 'Times',
               'verdana.afm' => 'Verdana',
            );

            $i = 0;
            foreach($fonts as $f)
            {
                $fontName = new MLabel($f,'blue',true); 
                if ($i++ == 5)
                {
                    $fontName->addBoxStyle('page-break-before','always');
                } 
                $fontName->fontFamily = $f;
                $fields[] = $fontName;
                $l = new MLabel("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec at odio vitae libero tempus convallis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum purus mauris, dapibus eu, sagittis quis, sagittis quis, mi. Morbi fringilla massa quis velit. Curabitur metus massa, semper mollis, molestie vel, adipiscing nec, massa. Phasellus vitae felis sed lectus dapibus facilisis. In ultrices sagittis ipsum. In at est. Integer iaculis turpis vel magna. Cras eu est. Integer porttitor ligula a tellus. Curabitur accumsan ipsum a velit. Sed laoreet lectus quis leo. Nulla pellentesque molestie ante. Quisque vestibulum est id justo.");
                $l->fontFamily = $f;
                $fields[] = $l;
            } 
            $this->addFields($fields);
        }
    }
?>