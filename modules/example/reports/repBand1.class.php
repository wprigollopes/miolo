<?
/*
   Cria um report com linhas de agrupamento
*/
    MIOLO::import('modules::tutorial::controls::mbandreport'); 

    class repBand1 extends MBandReport
    {
        public function __construct()
        {
            // nÃºmero de linhas por pÃ¡gina
            parent::__construct(NULL, NULL, 55);

            $this->titleReport = 'Exemplo MBandreport _ MultiLevel com Bands';

            // simula $query->result com multiplos niveis de quebra 
            $setor[1] = 'Setor 1';
            $setor[2] = 'Setor 2';
            $setor[3] = 'Setor 3';
            $conta[1] = '0000001';
            $conta[2] = '0000002';
            $conta[3] = '0000003';
            $conta[4] = '0000004';
            $tipo[1]  = 'DiÃ¡ria';
            $tipo[2]  = 'Passagem';
            $tipo[3]  = 'VeÃ­culo';
            for ($i = 1; $i < 4; $i++)     // level 1
            {
                for ($j = 1; $j < 5; $j++) // level 2
                {
                    for ($k = 1; $k < 4; $k++) // level 3
                    {
                        $r = rand(1, 15);
                        for ($l = 0; $l < $r; $l++) // data
                        {
                            $requisicao = substr(md5(uniqid()),1,8);
                            $datareq = '11/11/1981';
                            $nome = 'FULANO DE TAL REQUISITANTE';
                            $empresa = 'Empresa X';
                            $fatura = substr(md5(uniqid()),1,8);
                            $previsao = '11/12/1981';;
                            $valor = rand(1, 1000);
                            $data[] = array($setor[$i],$conta[$j],$tipo[$k], $requisicao, $datareq, $nome, $empresa, $fatura, $previsao, $valor);
                        }
                    }
                }
            }

            // cria a arvore de dados
            $t = new MTreeArray($data, '0,1,2', '3,4,5,6,7,8,9');

            $this->addGroupHeader(0,'Setor: $');
            $this->addGroupHeader(1,'Conta: $');
            $this->addGroupHeader(2,'Tipo de RequisiÃ§Ã£o: $');

            // define as colunas da linha de detalhe
            // ReportColumn: name,title,align,nowrap,width,visible,options
            $columns = array(
                new MPDFReportColumn('requisicao', '<b>RequisiÃ§Ã£o</b>', 'right', false, 10, true),
                new MPDFReportColumn('datareq', '<b>Data</b>', 'center', false, 10, true),
                new MPDFReportColumn('nome', '<b>Nome</b>', 'left', false, 25, true),
                new MPDFReportColumn('empresa', '<b>Empresa</b>', 'left', false, 15, true),
                new MPDFReportColumn('fatura', '<b>Fatura</b>', 'right', false, 15, true),
                new MPDFReportColumn('previsao', '<b>PrevisÃ£o</b>', 'right', false, 10, true),
                new MPDFReportColumn('valor', '<b>Valor Real</b>', 'right', false, 15, true),
            );

            // Largura do report, em %
            $this->setWidth(100);

            // registra as colunas de detalhes e summary
            $this->setColumns($columns);

            $this->generateBodyBand($t);
        }
    }
?>
