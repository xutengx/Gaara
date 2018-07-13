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
	public function __construct(FactoryFather $FactoryFather, Repository $Repository) {}
}


require '../Gaara/Core/Container_test.php';

$Container = new Container_test;


$Container->bind(FactoryInterface::class, FactoryFather::class);

$Container->bind(RepositoryInterface::class, RepositoryFather::class);

$Container->bind(StoreInterface::class, StoreFather::class);

$Container->bind(Factory::class);

//var_dump($Container);

$obj1 = $Container->make(Factory::class);

var_dump($obj1);


