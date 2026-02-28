<?
class BusinessExampleTipodocumento
  extends Business {
  public $codigo;
  public $descricao;

  public function businessExampleTipodocumento($data = null) {
    $this->business('admin', $data);
  }

  public function setData($data) {
    $this->codigo = $data->codigo;
    $this->descricao = $data->descricao;
  }

  public function &sqlAllFields() {
    return new sql("codigo, descricao", "tipodocumento");
  }

  public function &sqlUpdateAllFields() {
    return new sql("codigo, descricao", "tipodocumento");
  }

  public function getById($codigo) {
    $sql = $this->sqlAllFields();
    $sql->setWhere('codigo = ?');
    $query = $this->query($sql, $codigo);

    if (!$query->eof()) {
      $this->setData($query->getRowObject());
    }

    return $this;
  }

  public function insert() {
    $sql = $this->sqlAllFields();

    $args = array(
      $this->codigo,
      $this->descricao
    );

    $ok = $this->execute($sql->insert($args));

    return $ok;
  }

  public function update() {
    $sql = $this->sqlUpdateAllFields();
    $sql->setWhere('codigo = ?');
    $args = array(
      $this->codigo,
      $this->descricao,
      $this->codigo
    );
    $ok = $this->execute($sql->update($args));

    return $ok;
  }

  public function delete() {
    $sql = new sql('', 'tipodocumento', 'codigo = ?');
    $ok = $this->execute($sql->delete($this->codigo));

    if ($ok) {
      $this->log(OP_DEL, "codigo = $this->codigo; descricao = $this->descricao ");
    }

    return $ok;
  }

  public function listRange($range = NULL) {
    $sql = $this->sqlAllFields();

    $sql->setRange($range);
    $query = $this->query($sql);

    return $query;
  }
  public function listAll() {
    return $this->listRange();
  }
}
?>
