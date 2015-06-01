<?php
header("content-type:text/plain");
require_once("MemCachedClass.php");

Class test1 extends MemCachedClass {
    static private $heh;
    
    public function test() {
        $this->heh = "blah";
    }
}

$e = new test1();

function t() {
    global $e;
    $e->test();
//    $e->heh = "bleh";
}

t();

$e->test();
//$e->heh =" hahahahhaha";
//echo $e->heh;