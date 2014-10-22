<?php
class BaseClass {
	public function __destruct() 
	{ echo __METHOD__."\n"; }	// BaseClass::__destruct
	public function __construct() 
	{ echo __METHOD__."\n"; }
	public function __call($name,$arguments) 
	{ echo __METHOD__."\n"; }
	public function PrintOut($key1,$key2)
	{
		if(isset($this->$key1))
			unset($this->$key1);
		if(isset($this->$key2))
			unset($this->$key2);
		echo $this->value."\n";
	}
}
class A extends BaseClass {
	public function __destruct() 
	{ echo __METHOD__."\n"; parent::__destruct(); }
	public function __construct() 
	{ parent::__construct(); echo __METHOD__."\n"; }
	public static function __callStatic($name,$arguments) 
	{ echo __METHOD__."\n"; }
	public function __set($name,$value)
	{
		$this->$name = $value;
		$this->value++;
	}
	public function __get($name){ $this->value++; }
	public function __isset($name) { $this->value++; }
	public function __unset($name) { $this->value++; }
	public static function PrintOut2($key1,$key2)
	{
		echo __METHOD__."\n";
	}
}
class B extends A {
	protected $value = 0;
}

$A = new A();
$A->Temp((object)array(1,2));
unset($A);
A::Temp((object)array(2,3));
$B = new B();
A::PrintOut2($B->value1,$B->value2);
$B->PrintOut('value1','value2');
$B->value1 = 'Hello';
$B->value2 = 'World';
$B->PrintOut('value1','value2');
unset($B->value1);
unset($B->value2);
$B->PrintOut('value1','value2');
unset($B);
?>
