<?php

namespace mageekguy\atoum\tests\units\asserters;

use
	mageekguy\atoum,
	mageekguy\atoum\asserter,
	mageekguy\atoum\asserters\error as testedClass
;

require_once __DIR__ . '/../../runner.php';

class error extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->extends('mageekguy\atoum\asserter');
	}

	public function test__construct()
	{
		$this
			->if($asserter = new testedClass())
			->then
				->object($asserter->getGenerator())->isEqualTo(new asserter\generator())
				->object($asserter->getLocale())->isIdenticalTo($asserter->getGenerator()->getLocale())
				->object($asserter->getScore())->isInstanceOf('mageekguy\atoum\test\score')
				->variable($asserter->getMessage())->isNull()
				->variable($asserter->getType())->isNull()
			->if($asserter = new testedClass($generator = new asserter\generator(), $score = new atoum\test\score()))
			->then
				->object($asserter->getGenerator())->isIdenticalTo($generator)
				->object($asserter->getLocale())->isIdenticalTo($asserter->getGenerator()->getLocale())
				->object($asserter->getScore())->isIdenticalTo($score)
				->variable($asserter->getMessage())->isNull()
				->variable($asserter->getType())->isNull()
		;
	}

	public function testInitWithTest()
	{
		$this
			->if($asserter = new testedClass($generator = new asserter\generator()))
			->then
				->object($asserter->setWithTest($this))->isIdenticalTo($asserter)
				->object($asserter->getScore())->isIdenticalTo($this->getScore())
		;
	}

	public function testGetAsString()
	{
		$this
			->string(testedClass::getAsString(E_ERROR))->isEqualTo('E_ERROR')
			->string(testedClass::getAsString(E_WARNING))->isEqualTo('E_WARNING')
			->string(testedClass::getAsString(E_PARSE))->isEqualTo('E_PARSE')
			->string(testedClass::getAsString(E_NOTICE))->isEqualTo('E_NOTICE')
			->string(testedClass::getAsString(E_CORE_ERROR))->isEqualTo('E_CORE_ERROR')
			->string(testedClass::getAsString(E_CORE_WARNING))->isEqualTo('E_CORE_WARNING')
			->string(testedClass::getAsString(E_COMPILE_ERROR))->isEqualTo('E_COMPILE_ERROR')
			->string(testedClass::getAsString(E_COMPILE_WARNING))->isEqualTo('E_COMPILE_WARNING')
			->string(testedClass::getAsString(E_USER_ERROR))->isEqualTo('E_USER_ERROR')
			->string(testedClass::getAsString(E_USER_WARNING))->isEqualTo('E_USER_WARNING')
			->string(testedClass::getAsString(E_USER_NOTICE))->isEqualTo('E_USER_NOTICE')
			->string(testedClass::getAsString(E_STRICT))->isEqualTo('E_STRICT')
			->string(testedClass::getAsString(E_RECOVERABLE_ERROR))->isEqualTo('E_RECOVERABLE_ERROR')
			->string(testedClass::getAsString(E_DEPRECATED))->isEqualTo('E_DEPRECATED')
			->string(testedClass::getAsString(E_USER_DEPRECATED))->isEqualTo('E_USER_DEPRECATED')
			->string(testedClass::getAsString(E_ALL))->isEqualTo('E_ALL')
			->string(testedClass::getAsString('unknown error'))->isEqualTo('UNKNOWN')
		;
	}

	public function testSetWith()
	{
		$this
			->if($asserter = new testedClass($generator = new asserter\generator()))
			->then
				->object($asserter->setWith(null, null))->isIdenticalTo($asserter)
				->variable($asserter->getMessage())->isNull()
				->variable($asserter->getType())->isNull()
				->object($asserter->setWith($message = uniqid(), null))->isIdenticalTo($asserter)
				->string($asserter->getMessage())->isEqualTo($message)
				->variable($asserter->getType())->isNull()
				->object($asserter->setWith($message = uniqid(), $type = rand(0, PHP_INT_MAX)))->isIdenticalTo($asserter)
				->string($asserter->getMessage())->isEqualTo($message)
				->integer($asserter->getType())->isEqualTo($type)
		;
	}

	public function testExists()
	{
		$this
			->if($asserter = new testedClass($generator = new asserter\generator()))
			->then
				->exception(function() use (& $line, $asserter) { $asserter->exists(); })
					->isInstanceOf('mageekguy\atoum\asserter\exception')
					->hasMessage($generator->getLocale()->_('error does not exist'))
			->if($asserter->getScore()->addError(uniqid(), uniqid(), uniqid(), rand(1, PHP_INT_MAX), rand(0, PHP_INT_MAX), uniqid(), uniqid(), rand(1, PHP_INT_MAX)))
			->then
				->object($asserter->exists())->isIdenticalTo($asserter)
				->array($asserter->getScore()->getErrors())->isEmpty()
			->if($asserter->setWith($message = uniqid(), null))
			->then
				->exception(function() use (& $line, $asserter) { $asserter->exists(); })
					->isInstanceOf('mageekguy\atoum\asserter\exception')
					->hasMessage(sprintf($generator->getLocale()->_('error with message \'%s\' does not exist'), $message))
			->if($asserter->getScore()->addError(uniqid(), uniqid(), uniqid(), rand(1, PHP_INT_MAX), rand(0, PHP_INT_MAX), $message, uniqid(), rand(1, PHP_INT_MAX)))
			->then
				->object($asserter->exists())->isIdenticalTo($asserter)
				->array($asserter->getScore()->getErrors())->isEmpty()
			->if($asserter->setWith($message = uniqid(), $type = E_USER_ERROR))
			->then
				->exception(function() use (& $line, $asserter) { $line = __LINE__; $asserter->exists(); })
					->isInstanceOf('mageekguy\atoum\asserter\exception')
					->hasMessage(sprintf($generator->getLocale()->_('error of type %s with message \'%s\' does not exist'), testedClass::getAsString($type), $message))
			->if($asserter->getScore()->addError(uniqid(), uniqid(), uniqid(), rand(1, PHP_INT_MAX), $type, $message, uniqid(), rand(1, PHP_INT_MAX)))
			->then
				->object($asserter->exists())->isIdenticalTo($asserter)
				->array($asserter->getScore()->getErrors())->isEmpty()
			->if($asserter->setWith(null, $type = E_USER_ERROR))
			->then
				->exception(function() use (& $line, $asserter) { $line = __LINE__; $asserter->exists(); })
					->isInstanceOf('mageekguy\atoum\asserter\exception')
					->hasMessage(sprintf($generator->getLocale()->_('error of type %s does not exist'), testedClass::getAsString($type)))
			->if($asserter->getScore()->addError(uniqid(), uniqid(), uniqid(), rand(1, PHP_INT_MAX), $type, uniqid(), uniqid(), rand(1, PHP_INT_MAX)))
			->then
				->object($asserter->exists())->isIdenticalTo($asserter)
				->array($asserter->getScore()->getErrors())->isEmpty()
			->if($asserter->getScore()->addError(uniqid(), uniqid(), uniqid(), rand(1, PHP_INT_MAX), rand(1, PHP_INT_MAX), $message = uniqid() . 'FOO' . uniqid(), uniqid(), rand(1, PHP_INT_MAX)))
			->and($asserter->withPattern('/FOO/')->withType(null))
			->then
				->object($asserter->exists())->isIdenticalTo($asserter)
				->array($asserter->getScore()->getErrors())->isEmpty()
		;
	}

	public function testWithType()
	{
		$this
			->if($asserter = new testedClass(new asserter\generator()))
			->then
				->object($asserter->withType($type = rand(1, PHP_INT_MAX)))->isIdenticalTo($asserter)
				->integer($asserter->getType())->isEqualTo($type)
		;
	}

	public function testWithAnyType()
	{

		$this
			->if($asserter = new testedClass(new asserter\generator()))
			->and($asserter->withType(rand(1, PHP_INT_MAX)))
			->then
				->variable($asserter->getType())->isNotNull()
				->object($asserter->withAnyType())->isIdenticalTo($asserter)
				->variable($asserter->getType())->isNull()
		;
	}

	public function testWithMessage()
	{
		$this
			->if($asserter = new testedClass(new asserter\generator()))
			->then
				->object($asserter->withMessage($message = uniqid()))->isIdenticalTo($asserter)
				->string($asserter->getMessage())->isEqualTo($message)
				->boolean($asserter->messageIsPattern())->isFalse()
		;
	}

	public function testWithPattern()
	{
		$this
			->if($asserter = new testedClass(new asserter\generator()))
			->then
				->boolean($asserter->messageIsPattern())->isFalse()
				->object($asserter->withPattern($pattern = uniqid()))->isIdenticalTo($asserter)
				->string($asserter->getMessage())->isEqualTo($pattern)
				->boolean($asserter->messageIsPattern())->isTrue()
		;
	}

	public function testWithAnyMessage()
	{
		$this
			->if($asserter = new testedClass(new asserter\generator()))
			->and($asserter->withMessage(uniqid()))
			->then
				->variable($asserter->getMessage())->isNotNull()
				->boolean($asserter->messageIsPattern())->isFalse()
				->object($asserter->withAnyMessage())->isIdenticalTo($asserter)
				->variable($asserter->getMessage())->isNull()
				->boolean($asserter->messageIsPattern())->isFalse()
			->if($asserter->withPattern(uniqid()))
			->then
				->variable($asserter->getMessage())->isNotNull()
				->boolean($asserter->messageIsPattern())->isTrue()
				->object($asserter->withAnyMessage())->isIdenticalTo($asserter)
				->variable($asserter->getMessage())->isNull()
				->boolean($asserter->messageIsPattern())->isFalse()
		;
	}

	public function testSetScore()
	{
		$this
			->if($asserter = new testedClass())
			->then
				->object($asserter->setScore($score = new atoum\test\score()))->isIdenticalTo($asserter)
				->object($asserter->getScore())->isIdenticalTo($score)
				->object($asserter->setScore())->isIdenticalTo($asserter)
				->object($asserter->getScore())
					->isNotIdenticalTo($score)
					->isInstanceOf('mageekguy\atoum\score')
		;
	}
}
