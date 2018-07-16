<?php
declare(strict_types = 1);

// 接口
interface FactoryInterface{}
interface RepositoryInterface{}
interface StoreInterface{}

// 抽象类
abstract class FactoryAbstract implements FactoryInterface{}
abstract class RepositoryAbstract implements RepositoryInterface{}
abstract class StoreAbstract implements StoreInterface{}

// 父类
class FactoryFather extends FactoryAbstract{}
class RepositoryFather extends RepositoryAbstract{}
class StoreFather extends StoreAbstract{}

// 子类
class Factory extends FactoryFather{
	// 依赖另外2个接口
	public function __construct(RepositoryInterface $RepositoryInterface, StoreInterface $StoreInterface) {}
}
class Repository extends RepositoryFather{
	// 依赖另外2个父类
	public function __construct(FactoryFather $FactoryFather, StoreFather $StoreFather) {}
}
class Store extends StoreFather{
	// 依赖另外1个父类, 另外1个子类
	public function __construct(FactoryFather $FactoryFather, Repository $Repository, $t) {
		$this->FactoryFather = $FactoryFather;
		$this->Repository = $Repository;
		$this->t = $t;
	}
}


require '../Gaara/Core/Container_test.php';

$Container = new Container_test;


$Container->bind(FactoryInterface::class, FactoryFather::class);

$Container->bind(RepositoryInterface::class, RepositoryFather::class);

$Container->bind(StoreInterface::class, StoreFather::class);

$Container->bind(FactoryFather::class, null, true);

$Container->bind(Factory::class);

$Container->bind(Store::class);

$Container->bind('test_config', function(){
	return [
		'key' => '$value'
	];
});


//var_dump($Container);

$obj1 = $Container->make(Factory::class);
$obj2 = $Container->make(Store::class, [
	't' => 123
]);
$obj3 = $Container->make(Store::class, [
	't' => 123
]);


var_dump($obj2->FactoryFather === $obj3->FactoryFather );
var_dump($obj2 === $obj3 );


$config = $Container->make('test_config');
var_dump($config);
