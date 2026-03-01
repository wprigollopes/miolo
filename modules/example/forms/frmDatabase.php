<?php

class frmDatabase extends MForm
{
    public $box;
    public $module;

    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $this->module = MIOLO::getCurrentModule();

        $page = $MIOLO->getPage();
        parent::__construct(_M('Database Sample', $this->module));
        $this->defaultButton = false;
        $this->eventHandler();
    }

    public function createFields()
    {
        $action = $this->manager->getCurrentURL();

        $array_MLinkButton0 = array(
            new MLinkButton('btnSimple', _M('Simple query', $this->module), $action),
            new MLinkButton('btnWhere', _M('Condition', $this->module), $action),
            new MLinkButton('btnWhereMult', _M('Multiple condition', $this->module), $action),
            new MLinkButton('btnParam', _M('Parameter', $this->module), $action),
            new MLinkButton('btnParam2', _M('Parameters', $this->module), $action),
            new MLinkButton('btnUpdate', _M('Update', $this->module), $action),
        );
        $array_MLinkButton1 = array(
            new MLinkButton('btnOrder', _M('@1 clause', $this->module, 'ORDER BY'), $action),
            new MLinkButton('btnJoin', 'INNER JOIN', $action),
            new MLinkButton('btnHaving', _M('@1 clause', $this->module, 'HAVING'), $action),
        );
        $array_MLinkButton2 = array(
            new MLinkButton('btnSubquery', _M('Subquery', $this->module), $action),
            new MLinkButton('btnGetRow', _M('GetRowValues', $this->module), $action),
            new MLinkButton('btnNav', _M('Navigation', $this->module), $action),
            new MLinkButton('btnRaw', _M('RawData', $this->module), $action)
        );
        $array_MLinkButton3 = array(
            new MLinkButton('btnCount', 'COUNT', $action),
            new MLinkButton('btnRange', 'ListRange', $action),
            new MLinkButton('btnSqlRange', 'SQLRange', $action),
            new MLinkButton('btnPag', _M('Pagination', $this->module), $action),
            new MLinkButton('btnSequence', _M('Sequence', $this->module), $action),
            new MLinkButton('btnTrans', _M('Transactions', $this->module), $action)
        );
        $array_MLinkButton4 = array(
            new MLinkButton('btnOrderRs', _M('Order Result', $this->module), $action),
            new MLinkButton('btnFilterRs', _M('Filter Result', $this->module), $action),
            new MLinkButton('btnFilterReRs', _M('Filter Reg Expression Result', $this->module), $action),
            new MLinkButton('btnBind', _M('Bind', $this->module), $action),
        );
        $array_MLinkButton5 = array(
            new MLinkButton('btnExceptionQuery', 'EDatabaseException - Query', $action),
            new MLinkButton('btnExceptionExec', 'EDatabaseException - Exec', $action),
            new MLinkButton('btnDirectSQL', 'DirectSQL', $action),
            new MLinkButton('btnSQLCommand', 'SQLCommand', $action),
            new MLinkButton('btnOpenClose', _M('Open/Close DB', $this->module), $action),
        );
        $fields = array(
            new MTextLabel('label1', 'miolo_transaction', _M('Tables', $this->module)),
            new MTextLabel('label2', 'seq_miolo_transaction', _M('Sequence', $this->module)),
            new MLinkButtonGroup('lbGrp0', '', $array_MLinkButton0, 'horizontal'),
            new MLinkButtonGroup('lbGrp1', '', $array_MLinkButton1, 'horizontal'),
            new MLinkButtonGroup('lbGrp2', '', $array_MLinkButton2, 'horizontal'),
            new MLinkButtonGroup('lbGrp3', '', $array_MLinkButton3, 'horizontal'),
            new MLinkButtonGroup('lbGrp4', '', $array_MLinkButton4, 'horizontal'),
            new MLinkButtonGroup('lbGrp5', '', $array_MLinkButton5, 'horizontal')
        );
        $this->setFields($fields);
    }

    public function btnSimple_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $text = '#records: ' . $n . '<br><br>';
        $text .= '#rec - transação' . '<br>';
        $result = $query->result;
        for ( $i = 0; $i < $n; $i++ )
        {
            $text .= "#$i - " . $result[$i][1] . '<br>';
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnWhere_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction', 'idtransaction=2');
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $text = 'idtransaction = 2   -  #records: ' . $n . '<br><br>';
        $text .= '#rec - transação' . '<br>';
        $result = $query->result;
        for ( $i = 0; $i < $n; $i++ )
        {
            $text .= "#$i - " . $result[$i][1] . '<br>';
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnWhereMult_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $sql->setWhere('idtransaction < 100');
        $sql->setWhereAnd("m_transaction LIKE 'A%'");
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $text = '(idtransaction < 100) and (m_transaction LIKE A%)  -  #records: ' . $n . '<br><br>';
        $text .= '#rec - transação' . '<br>';
        $result = $query->result;
        for ( $i = 0; $i < $n; $i++ )
        {
            $text .= "#$i - " . $result[$i][1] . '<br>';
        }
        $this->addField(new MThemeBox('Results - Multiple Where', $text));
    }

    public function btnParam_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction', 'idtransaction=?');
        $sql->setParameters(3);
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $text = 'idtransaction = 4   -  #records: ' . $n . '<br><br>';
        $row = $query->getRowValues();
        foreach ( $row as $f )
        {
            $text .= $f . '<br>';
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnParam2_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction', "(idtransaction < ?) AND (m_transaction LIKE ?)");
        $sql->setParameters(array( 100, 'A%' ));
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $text = '(idtransaction < 100) and (m_transaction LIKE A%)  -  #records: ' . $n . '<br><br>';
        for ( $i = 0; $i < $n; $i++ )
        {
            $text .= "#$i - " . $query->result[$i][1] . '<br>';
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnUpdate_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('nickname,confirm_hash', 'miolo_user', "(iduser=1)");
        $sql->setParameters(array( 'tron', ':NULL' ));
        try
        {
            $db->execute($sql->update());
            $text = 'Update ok! : ' . $sql->command;
        }
        catch ( EDatabaseException $e )
        {
            $text = $e->getMessage();
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnOrder_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction', '', 'm_transaction');
        $query = $db->getQuery($sql);
        $table = new TableRaw('select * from miolo_transaction', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnJoin_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('t.m_transaction, g.m_group, a.rights', '', '', 't.m_transaction, g.m_group');
        $sql->setJoin('miolo_transaction t', 'miolo_access a', '(t.idtransaction=a.idtransaction)');
        $sql->setJoin('miolo_access a', 'miolo_group g', '(g.idgroup=a.idgroup)');
        $query = $db->getQuery($sql);
        $table = new TableRaw('Join miolo_group * miolo_rights * miolo_transaction', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnHaving_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('g.m_group, count(*)', '', '', 't.m_transaction, g.m_group', 'g.m_group', 'count(*) > 2');
        $sql->setJoin('miolo_transaction t', 'miolo_access a', '(t.idtransaction=a.idtransaction)');
        $sql->setJoin('miolo_access a', 'miolo_group g', '(g.idgroup=a.idgroup)');
        $query = $db->getQuery($sql);
        $table = new TableRaw('Clausula having', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnSubquery_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('r.idgroup', 'miolo_access r');
        $sql->setWhere('r.idtransaction IN ?');
        $sqlx = new MSQL('t.idtransaction', 'miolo_transaction t', "(t.m_transaction LIKE 'A%')");
        $sql->setParameters(':(' . $sqlx->select() . ')');
        $query = $db->getQuery($sql);
        $table = new TableRaw('Subquery: ' . $query->sql, $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnGetRow_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $n = $query->getRowCount();
        $query->moveTo(4);
        $text = 'Row = 4   -  #records: ' . $n . '<br><br>';
        $row = $query->getRowValues();
        foreach ( $row as $f )
        {
            $text .= $f . '<br>';
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnNav_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $text = 'Move to First: <br>';
        $query->moveFirst();
        $row = $query->getRowValues();
        foreach ( $row as $f )
        {
            $text .= $f . ' - ';
        }
        $text .= '<br><br>';
        $text .= 'Move to Last: <br>';
        $query->moveLast();
        $row = $query->getRowValues();
        foreach ( $row as $f )
        {
            $text .= $f . ' - ';
        }
        $text .= '<br><br>';
        $text .= 'Transverse: <br>';
        $query->moveFirst();
        while ( !$query->eof )
        {
            $text .= $query->fields('m_transaction') . '<br>';
            $query->moveNext();
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnRaw_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $table = new TableRaw('select * from miolo_transaction', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnCount_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $n = $db->count($sql);
        $command = $sql->select();
        $text = "$command => #records: " . $n . '<br><br>';
        $sql = new MSQL('*', 'miolo_transaction', '(idtransaction > 100)');
        $n = $db->count($sql);
        $command = $sql->select();
        $text .= "$command => #records: " . $n . '<br><br>';
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnRange_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $n = $db->count($sql);
        $totalpages = (int) $n / 5;
        for ( $page = 1; $page <= $totalpages; $page++ )
        {
            $range = new QueryRange($page, 5);
            $result = $db->queryRange($sql->select(), $range)->result;
            $n = $range->total;
            $text .= "Page: $page  [$n records]: <br>";
            $text .= '          #rec - transação' . '<br>';
            for ( $i = 0; $i < $n; $i++ )
            {
                $text .= "         #$i - " . $result[$i][1] . '<br>';
            }
            $text .= "<br>";
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnSqlRange_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $n = $db->count($sql);
        $totalpages = (int) $n / 5;
        for ( $page = 1; $page <= $totalpages; $page++ )
        {
            $sql->setRange($page, 5);
            $query = $db->getQuery($sql);
            $result = $query->result;
            $n = $query->getRowCount();
            $text .= "Page: $page  [$n records]: <br>";
            $text .= '          #rec - transação' . '<br>';
            for ( $i = 0; $i < $n; $i++ )
            {
                $text .= "         #$i - " . $result[$i][1] . '<br>';
            }
            $text .= "<br>";
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnPag_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $query->setPageLength(10);
        $n = $query->getPageCount();
        $text = "<b>$n pages</b><br><br>";
        for ( $page = 1; $page <= $n; $page++ )
        {
            $result = $query->getPage($page);
            $m = count($result);
            $text .= "Page: $page  [$m records]: <br>";
            $text .= '          #rec - transação' . '<br>';
            for ( $i = 0; $i < $m; $i++ )
            {
                $text .= "         #$i - " . $result[$i][1] . '<br>';
            }
            $text .= "<br>";
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnSequence_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        for ( $i = 0; $i <= 5; $i++ )
        {
            $id = $db->getNewId('seq_miolo_log', 'miolo_sequence');
            $text .= "id = $id <br>";
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnTrans_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('admin');
// Exemplo com Commit
        $id = $db->getNewId('seq_miolo_transaction', 'miolo_sequence');
        $sql = new MSQL('idtransaction, m_transaction', 'miolo_transaction');
        $args = array( $id, 'Teste' );
        $cmd = array( );
        $cmd[] = $sql->insert($args);
        $sql->__construct('idtransaction, idgroup, rights', 'miolo_access');
        $cmd[] = $sql->insert(array( $id, 1, 1 ));
        $cmd[] = $sql->insert(array( $id, 2, 1 ));
        $cmd[] = $sql->insert(array( $id, 3, 1 ));
        $cmd[] = $sql->insert(array( $id, 4, 1 ));
        $text = "id = $id <br>";
        try
        {
            $db->execute($cmd);
            $text .= "Transaction Ok";
        }
        catch ( EDatabaseException $e )
        {
            $text .= "Transaction Fail: " . $e->getMessage();
        }
        $text .= '<br>';
// Exemplo com RollBack
        $id = $db->getNewId('seq_miolo_transaction', 'miolo_sequence');
        $sql = new MSQL('idtransaction, m_transaction', 'miolo_transaction');
        $args = array( $id, 'Teste2' );
        $cmd = array( );
        $cmd[] = $sql->insert($args);
        $sql->__construct('idtransaction, idgroup, rights', 'miolo_access');
        $cmd[] = $sql->insert(array( $id, 1, 1 ));
        $cmd[] = $sql->insert(array( $id, 2, 1 ));
        $cmd[] = $sql->insert(array( ':NULL', 3, 1 )); // este comando gera erro!
        $cmd[] = $sql->insert(array( $id, 4, 1 ));
        $text .= "id = $id <br>";
        try
        {
            $db->execute($cmd);
            $text .= "Transaction Ok";
        }
        catch ( EDatabaseException $e )
        {
            $text .= "Transaction Fail: " . $e->getMessage();
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnOrderRs_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $table = new TableRaw('ResultSet before order', $query->result);
        $this->addField(new MThemeBox('Results - before order', $table));
        $query->setOrder('m_transaction', 'SORT_DESC');
        $table = new TableRaw('ResultSet after order', $query->result);
        $query->setOrder('m_transaction');
        $tableA = new TableRaw('ResultSet after ReOrder', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
        $this->addField(new MThemeBox(_M('Results', $this->module), $tableA));
    }

    public function btnFilterRs_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $table = new TableRaw('ResultSet without filter', $query->result);
        $this->addField(new MThemeBox('Results - without filter', $table));
        $query->addFilter('m_transaction', 'LIKE', "T%");
        $query->applyFilter();
        $table = new MTableRaw('ResultSet with filter', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnFilterReRs_click()
    {
        $MIOLO = MIOLO::getInstance();
        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction');
        $query = $db->getQuery($sql);
        $table = new MTableRaw('ResultSet without filter', $query->result);
        $this->addField(new MThemeBox('Results - without filter', $table));
        $query->addFilter('m_transaction', 'regex', '^(.*?)A(.*)');
        $query->applyFilter();
        $table = new MTableRaw('ResultSet with filter - RegEx: [^(.*?)A(.*)]', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnBind_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('admin');
        $sql = new MSQL('*', 'miolo_transaction', 'idtransaction=:idtransaction');
        for ( $i = 1; $i < 6; $i++ )
        {
            $sql->bind(array( ':idtransaction' => $i ));
            $query = $db->getQuery($sql);
            $n = $query->getRowCount();
            $row = $query->getRowValues();
            foreach ( $row as $f )
            {
                $text .= $f . '<br>';
            }
        }
        $query->close();
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnExceptionQuery_click()
    {
        $MIOLO = MIOLO::getInstance();

        $text = '';
        $db = $MIOLO->getDatabase('admin');
        try
        {
            $sql = new MSQL('*', 'miolo_transaction', "m_transaction like 'A%'");
            $query = $db->getQuery($sql);
            $text .= "- Comando: " . $sql->command . " : OK <br>";
            $sql = new MSQL('*', 'cm_transaca', "m_transaction like 'A%'"); // gera erro !
            $query = $db->getQuery($sql);
            $text .= "- Comando: " . $sql->command . " : OK<br>";
        }
        catch ( EDatabaseException $e )
        {
            $text .= "- Comando: " . $sql->command . " : Fail : " . $e->getMessage();
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnExceptionExec_click()
    {
        $MIOLO = MIOLO::getInstance();

        $text = '';
        $db = $MIOLO->getDatabase('admin');
        try
        {
            $id = $db->getNewId('seq_miolo_transaction', 'miolo_sequence');
            $sql = new MSQL('idtransaction, m_transaction', 'miolo_transaction');
            $args = array( $id, 'Teste1 Example' );
            $db->execute($sql->insert($args));
            $text .= "- Command: " . $sql->command . " : OK <br>";
            $id = $db->getNewId('seq_miolo_transaction', 'miolo_sequence');
            $sql = new MSQL('idtran, m_transaction', 'miolo_transaction'); // gera erro!
            $args = array( $id, 'Teste2 Example' );
            $db->execute($sql->insert($args));
            $text .= "- Command: " . $sql->command . " : OK <br>";
        }
        catch ( EDatabaseException $e )
        {
            $text .= "- Comando: " . $sql->command . " : Fail : " . $e->getMessage();
        }
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }

    public function btnDirectSQL_click()
    {
        $MIOLO = MIOLO::getInstance();

        $text = '';
        $db = $MIOLO->getDatabase('admin');
        $query = $db->query('SELECT g.m_group, t.m_transaction, a.rights FROM miolo_transaction t, miolo_group g, miolo_access a WHERE (a.idtransaction=t.idtransaction) AND (a.idgroup = g.idgroup) ORDER BY m_group,m_transaction');
        $table = new MTableRaw('ResultSet', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnSQLCommand_click()
    {
        $MIOLO = MIOLO::getInstance();

        $text = '';
        $db = $MIOLO->getDatabase('admin');
        $query = $db->getQueryCommand('SELECT g.m_group, t.m_transaction, a.rights FROM miolo_transaction t, miolo_group g, miolo_access a WHERE (a.idtransaction=t.idtransaction) AND (a.idgroup = g.idgroup) ORDER BY m_group,m_transaction');
        $table = new MTableRaw('ResultSet', $query->result);
        $this->addField(new MThemeBox(_M('Results', $this->module), $table));
    }

    public function btnOpenClose_click()
    {
        $MIOLO = MIOLO::getInstance();

        $db = $MIOLO->getDatabase('admin');
        $text = $db->conf . ': ' . $db->status;
        $db->close();
        $text .= ' - ' . $db->conf . ': ' . $db->status;
        $db = $MIOLO->getDatabase('admin'); // the same object is retrieved, and opened
        $text .= ' - ' . $db->conf . ': ' . $db->status;
        $this->addField(new MThemeBox(_M('Results', $this->module), $text));
    }
}
