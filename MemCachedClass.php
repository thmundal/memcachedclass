<?php

Class MemCachedClass {
    private $memcached = false;
    private $prefix;
    
    public function __construct($server = ["ip" => "localhost", "port" => 11211]) {
        if(!class_exists("Memcached"))
            throw new MemCachedClass_exception("Memcached is not installed with php");
        
        $this->memcached = new Memcached();
        $this->memcached->addServer($server["ip"], $server["port"]);
        $this->prefix = get_class()."::".get_class($this)."::";
    }
    
    public function validate($b, $name) {
        if(sizeof($b) <= 1 OR get_parent_class($b[1]['class']) != get_class())
            throw new MemCachedClass_exception("Cannot access private member ".$name);
    }
    
    public function __set($name, $value) {
        $this->validate(debug_backtrace(), $name);
        $this->set($name, $value);
    }
    
    public function __get($name) {
        $this->validate(debug_backtrace(), $name);
        return $this->get($name);
    }
    
    public function set($name, $value) {
        $this->memcached->set($this->prefix.$name, $value);        
    }
    
    public function get($name) {
        return $this->memcached->get($this->prefix.$name);        
    }
}


Class MemCachedClass_exception extends Exception {
    public function __toString() {
        ob_end_clean();
        echo "<pre>" . __CLASS__ . ": " . $this->getMessage() . " | file: " .$this->getFile() . " line: " . $this->getLine() . "\n" . $this->getTraceAsString()."</pre>";
        exit;
        return "";
    }
}