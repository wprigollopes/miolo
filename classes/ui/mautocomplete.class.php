<?php

    class MAutoComplete extends MBusiness
    {
    
        public $module;
        public $item;
        public $value;
        public $config;
        public $sql;
        public $defaults;
        public $result;
        
        public function __construct($module,$item,$value,$defaults=null)
        {
            $this->value    = $value;
            $this->module   = $module;
            $this->item     = $item;
            $this->defaults = $defaults;
            parent::__construct($module);
            
            $MIOLO = MIOLO::getInstance();
            
            if( ( file_exists( $MIOLO->getModulePath( $module, 'db/lookup.class')) 
                    && $MIOLO->uses('/db/lookup.class',$module) )
                || $MIOLO->uses('/classes/lookup.class',$module) )
            {
                $className = 'Business' . $module . 'Lookup';
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $module) || !class_exists($className)) {
                    return;
                }
                $object = new $className();

                $method = 'autoComplete' . $item;
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $item) || !method_exists($object, $method)) {
                    return;
                }
                $info = $object->$method($this, $defaults);
                parent::__construct($this->module);

                if($info)
                {
                    $this->result  = $info;
                }
                else
                {
                    //faz consulta
                    $sql = new Msql('');
                    $sql->createFrom($this->sql);
                    $sql->prepare($value);
                    $db = $MIOLO->getDatabase ( $this->module );
                    $result = $db->query($value ? $sql->command : str_replace('?','NULL',$this->sql));
                    $this->result = $result[0];
                }
            }
        }

        public function getResult()
        {
            return $this->result;
        }
        
        public function setContext($config,$sql)
        {
            $this->config = $config;
            $this->module = $config;
            $this->sql    = $sql;
        }

    }
