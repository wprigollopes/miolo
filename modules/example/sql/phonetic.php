<?
ini_set("memory_limit","64M");
PutEnv("ORACLE_SID=UFJF");
PutEnv("ORACLE_HOME=/home/oracle/product/8.1.7");

chdir('/home/ematos/public_html/miolo/classes');
include_once '/home/ematos/public_html/miolo/classes/miolo.class';
include_once '/home/ematos/public_html/miolo/classes/support.inc';

$MIOLO = MIOLO::GetInstance();
$MIOLO->conf = new MConfigLoader();
$MIOLO->conf->LoadConf();

$MIOLO->setConf('logs.port','0');

$MIOLO->Init();
$MIOLO->history = new MHistory($MIOLO);

chdir('/home/ematos/public_html/miolo/modules/tutorial/sql');

try
{
   $db = $MIOLO->GetDatabase('ufjf');
   $isr = $db->GetISR();

/*
   $sql = new sql('p.idpessoa,p.nome','cm_pessoa p, rh_funcionario f'); 
   $sql->setJoin('cm_pessoa p','rh_funcionario f', 'p.idpessoa=f.idpessoa');
*/
    for($i = 0; $i < 600000; $i+=1000)
    {
       $sql = new sql('idpessoa,nome','cm_pessoa'); 
       $sql->setOffset($i,1000);

       $n = 0;
       $query = $db->GetQuery($sql);
       while ((!$query->eof))
       {
          $key = $query->fields('idpessoa');
          $nome = $query->fields('nome');
          try 
          {
             $isr->indexer('cm_pessoa','nome',$key, $nome, true);
          }
          catch (Exception $e) 
          {
             print $e->getMessage() . "\n";
          }
          if (($n++ % 100) === 0) print ".";
          $query->MoveNext();
       }
    }
}
catch (Exception $e) 
{
    print $e->getMessage() . "\n";
}
