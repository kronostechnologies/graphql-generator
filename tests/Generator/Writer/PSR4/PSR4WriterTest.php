<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Writer\PSR4\PSR4Resolver;
use GraphQLGen\Generator\Writer\PSR4\PSR4Writer;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4WriterTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'ScalarType';
	/**
	 * @var PSR4WriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_psr4WriterContext;

	/**
	 * @var PSR4Writer
	 */
	protected $_psr4Writer;

	/**
	 * @var PSR4Resolver|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_psr4Resolver;

	public function setUp() {
		$this->_psr4Resolver = $this->createMock(PSR4Resolver::class);
		$this->_psr4Resolver->method('generateTokensFromDependencies')->willReturn([]);

		$this->_psr4WriterContext = $this->createMock(PSR4WriterContext::class);
		$this->_psr4WriterContext->resolver = $this->_psr4Resolver;
		$this->_psr4WriterContext->formatter = new StubFormatter();

		$this->_psr4Writer = new PSR4Writer($this->_psr4WriterContext);
	}

	public function test_GivenNothing_initialize_WillCreateDirectoryStructure() {
		$this->_psr4WriterContext->expects($this->any())->method('createDirectoryIfNonExistant');

		$this->_psr4Writer->initialize();
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillGetProperStubFileName() {
		$scalarType = $this->GivenScalarType();

		$this->_psr4Resolver->expects($this->once())->method('getStubFilenameForType');

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}


	public function test_GivenScalarType_generateFileForTypeGenerator_WillStoreFQNToken() {
		$scalarType = $this->GivenScalarType();

		$this->_psr4Resolver->expects($this->once())->method('storeFQNTokenForType');

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillWriteClassFile() {
		$scalarType = $this->GivenScalarType();

		$this->_psr4WriterContext->expects($this->once())->method('writeFile');

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenNothing_finalize_WillGetResolvedTokens() {
		$this->_psr4Resolver->expects($this->any())->method('getAllResolvedTokens')->willReturn([]);

		$this->_psr4Writer->finalize();
	}

	public function test_GivenAtLeastOneResolveToken_finalize_WillCheckIfFQNExists() {
		$this->GivenAtLeastOneResolveToken();

		$this->_psr4WriterContext->expects($this->once())->method('fileExists');

		$this->_psr4Writer->finalize();
	}

	public function test_GivenAtLeastOneResolveToken_finalize_WillReplaceFile() {
		$this->GivenAtLeastOneResolveToken();

		$this->_psr4WriterContext->expects($this->once())->method('writeFile');

		$this->_psr4Writer->finalize();
	}

	protected function GivenScalarType() {
		return new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		);
	}

	protected function GivenAtLeastOneResolveToken() {
		$this->_psr4Resolver->method('getAllResolvedTokens')->willReturn([ '123' => 'NS\123']);
	}
}