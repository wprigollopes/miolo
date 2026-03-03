<?php

class MSimpleTable extends MTable
{
    public $cell = array();
    public $attributes;

    public function __construct($name='', $attrs='', $row=1, $cell=1)
    {
        parent::__construct();
        $this->setName($name);
        
        $this->setAttributes($attrs);
        // Assim como acontece na MLookupContainer, $this->attrs->items['class'] não é um atributo válido, logo, pode ser removido:
        // $this->setClass('mSimpleTable ' . $this->attrs->items['class']);
        $this->setClass('mSimpleTable ');
        for($i=0; $i < $row; $i++)
        {
            $this->attributes['row'][$i] = '';
            for($j=0; $j < $cell; $j++)
            {
                $this->attributes['cell'][$i][$j] = '';
            }
        }
   }

   private function setTableAttribute($area, $i, $j=NULL,$name, $attr)
   {
        /*
         * Usar .= nos índices $area/$i/$j quando estes ainda não existem emite um notice.
         * É necessário inicializá-los primeiro como uma string antes de tentar concatenar.
         */
        $at = ($attr != '') ? " $name=\"$attr\" " : " $name ";
        if (is_null($j))
        {
            if (!isset($this->attributes[$area][$i]))
            {
                $this->attributes[$area][$i] = $at;
            }
            else
            {
                $this->attributes[$area][$i] .= $at;
            }
        }
        else
        {
            if (!isset($this->attributes[$area][$i][$j]))
            {
                $this->attributes[$area][$i][$j] = $at;
            }
            else
            {
                $this->attributes[$area][$i][$j] .= $at;
            }
        }
    }

    private function setTableClass($area, $i,$j=NULL,$class)
    {
        /*
         * Usar .= nos índices $area/$i/$j quando estes ainda não existem emite um notice.
         * É necessário inicializá-los primeiro como uma string antes de tentar concatenar.
         */
        if (is_null($j))
        {
            if (!isset($this->attributes[$area][$i]))
            {
                $this->attributes[$area][$i] = " class=\"$class\" ";
            }
            else
            {
                $this->attributes[$area][$i] .= " class=\"$class\" ";
            }
        }
        else
        {
            if (!isset($this->attributes[$area][$i][$j]))
            {
                $this->attributes[$area][$i][$j] = " class=\"$class\" ";
            }
            else
            {
                $this->attributes[$area][$i][$j] .= " class=\"$class\" ";
            }
        }
    }

   public function setRowAttribute($i, $name, $attr)
   {
       $this->setTableAttribute('row',$i,NULL,$name, $attr);
   }

   public function setCellAttribute($i, $j, $name, $attr='')
   {
       $this->setTableAttribute('cell',$i,$j,$name, $attr);
   }

   public function setHeadAttribute($i, $name, $attr='')
   {
       $this->setTableAttribute('head',$i,NULL,$name, $attr);
   }

   public function setFootAttribute($i, $name, $attr='')
   {
       $this->setTableAttribute('foot',$i,NULL,$name, $attr);
   }

   public function setRowClass($i,$class)
   {
       $this->setTableClass('row',$i,NULL,$class);
   }

   public function setCellClass($i,$j,$class)
   {
       $this->setTableClass('cell',$i,$j,$class);
   }

   public function setHeadClass($i,$class)
   {
       $this->setTableClass('head',$i,NULL,$class);
   }

   public function setFootClass($i,$class)
   {
       $this->setTableClass('foot',$i,NULL,$class);
   }

    public function setCell($i,$j,$content,$attrs='')
    {
        $this->cell[$i][$j] = $content;
        if ($attrs != '')
        {
            // Usar o operador de concatenação em uma variável que ainda não existe gera um notice.
            // É necessário inicializar a string quando for acessar um determinado índice pela primeira vez.
            if (isset($this->attributes['cell'][$i][$j]))
            {
                $this->attributes['cell'][$i][$j] .= $attrs;
            }
            else
            {
                $this->attributes['cell'][$i][$j] = $attrs;
            }
        }
    }

    /*
     * $content = null só para compatibilizar a assinatura com a classe pai.
     */
    public function setHead($i, $content = null, $attrs = '')
    {
        $this->head[$i] = $content;
        if ($attrs != '')
        {
            // Usar o operador de concatenação em uma variável que ainda não existe gera um notice.
            // É necessário inicializar a string quando for acessar um determinado índice pela primeira vez.
            if (isset($this->attributes['head'][$i]))
            {
                $this->attributes['head'][$i] .= $attrs;
            }
            else
            {
                $this->attributes['head'][$i] = $attrs;
            }
        }
    }

   /*
    * $content = null só para compatibilizar a assinatura com a classe pai.
    */
   public function setFoot($i, $content = null, $attrs = '')
   {
       $this->foot[$i] = $content;
       if ($attrs != '')
       {
          $this->attributes['foot'][$i] .= $attrs;
       }
   }

   public function setColGroup($i,$attrs='')
   {
       $this->colgroup[$i]['attr'] = $attrs;
   }

   public function setColGroupCol($i,$j,$attrs='')
   {
       $this->colgroup[$i]['col'][$j] = $attrs;
   }

   public function generate()
   {
        $n = count($this->head);
        for($i=0; $i<$n; $i++)
        {
            $head[$i] = $this->painter->generateToString($this->head[$i]);
        }
        $n = count($this->foot);
        for($i=0; $i<$n; $i++)
        {
            $foot[$i] = $this->painter->generateToString($this->foot[$i]);
        }
        $n = count($this->cell);
        $body = array();
        for($i=0; $i<$n; $i++)
        {
            $k = count($this->cell[$i]);
            for($j=0; $j<$k; $j++)
            {
                $body[$i][$j] = $this->painter->generateToString($this->cell[$i][$j]);
            }
        }
      
        $this->setBody($body);
        $this->setAttr($this->attributes);
        return $this->getRender('table');
   }

}

?>