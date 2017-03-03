<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\PSR4ClassFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4ClassWriter;
use GraphQLGen\Generator\Writer\PSR4\PSR4Resolver;
use GraphQLGen\Generator\Writer\PSR4\PSR4StubFile;
use GraphQLGen\Generator\Writer\PSR4\TypeFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4Writer;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4ClassWriterTest extends \PHPUnit_Framework_TestCase {
	const FIELD_1_NAME = 'FirstField';
	const FIELD_1_DESC = 'This is the first field';
	const FIELD_1_TYPE_NAME = 'Int';
	const FIELD_2_NAME = 'SecondField';
	const FIELD_2_DESC = 'This is the second field';
	const FIELD_2_TYPE_NAME = 'Bool';
	const PRIMARY_TYPE_NAME = 'TestEntity';

	const ACTUAL_NAMESPACE = 'Types';

	/**
	 * @var PSR4StubFile|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFile;

	/**
	 * @var PSR4WriterContext
	 */
	protected $_psr4Context;

	/**
	 * @var PSR4ClassWriter
	 */
	protected $_classWriter;

	/**
	 * @var Type
	 */
	protected $_givenType;

	/**
	 * @var PSR4ClassFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_psr4Formatter;

	/**
	 * @var PSR4Resolver|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_psr4Resolver;

	public function setUp() {
		$this->_stubFile = $this->createMock(PSR4StubFile::class);

		$this->_psr4Context = new PSR4WriterContext();
		$this->_psr4Context->formatter = new StubFormatter(
			true,
			4,
			",",
			new TypeFormatter(),
			true
		);

		$this->_psr4Resolver =
			$this
				->getMockBuilder(PSR4Resolver::class)
				->enableOriginalConstructor()
				->setConstructorArgs([""])
				->getMock();

		$this->_psr4Resolver->method('generateTokensFromDependencies')->willReturn([]);

		$this->_psr4Context->resolver = $this->_psr4Resolver;

		$this->_psr4Formatter =
			$this
				->getMockBuilder(PSR4ClassFormatter::class)
				->enableOriginalConstructor()
				->setConstructorArgs([$this->_psr4Context->formatter, $this->_stubFile])
				->getMock();

		$this->_givenType = $this->GivenType();

		$this->_classWriter =
			$this
				->getMockBuilder(PSR4ClassWriter::class)
				->enableOriginalConstructor()
				->setConstructorArgs([$this->_givenType, $this->_psr4Context, $this->_stubFile, $this->_psr4Formatter])
				->setMethods(['writeClassToFile'])
				->getMock();
	}

	public function test_GivenType_getClassName_WillFetchName() {
		$retVal = $this->_classWriter->getClassName();

		$this->assertEquals(self::PRIMARY_TYPE_NAME, $retVal);
	}

	public function test_GivenTypeContextConstants_getVariablesDeclarationFormatted_WillCallGetFormattedVariablesDeclaration() {
		$this->_psr4Context->formatter->useConstantsForEnums = true;

		$this->_psr4Formatter->expects($this->once())->method('getFormattedVariablesDeclaration');

		$this->_classWriter->getVariablesDeclarationFormatted();
	}

	public function test_GivenType_getNamespace_WillGetNamespaceForType() {
		$this->_psr4Resolver->expects($this->once())->method('getNamespaceForType');

		$this->_classWriter->getNamespace();
	}

	public function test_GivenType_getUsesTokens_WillGenerateTokensFromDependencies() {
		$this->_psr4Resolver->expects($this->once())->method('generateTokensFromDependencies');

		$this->_classWriter->getImportedNamespacesTokens();
	}

	public function test_GivenType_getClassFilePath_WillGetFilePathSuffixForFQN() {
		$this->_psr4Resolver->expects($this->once())->method('getFilePathSuffixForFQN');

		$this->_classWriter->getFilePath();
	}

	public function test_GivenType_replacePlaceholdersAndWriteToFile_WillCallStubWriteTypeDefinition() {
		$this->_stubFile->expects($this->once())->method('writeTypeDefinition');

		$this->_classWriter->replacePlaceholders();
	}

	public function test_GivenType_replacePlaceholdersAndWriteToFile_WillCallStubWriteClassName() {
		$this->_stubFile->expects($this->once())->method('writeClassName');

		$this->_classWriter->replacePlaceholders();
	}

	public function test_GivenType_replacePlaceholdersAndWriteToFile_WillCallStubWriteNamespace() {
		$this->_stubFile->expects($this->once())->method('writeOrStripNamespace');

		$this->_classWriter->replacePlaceholders();
	}

	public function test_GivenType_replacePlaceholdersAndWriteToFile_WillCallStubWriteVariablesDeclarations() {
		$this->_stubFile->expects($this->once())->method('writeVariablesDeclarations');

		$this->_classWriter->replacePlaceholders();
	}

	public function test_GivenType_replacePlaceholdersAndWriteToFile_WillCallStubWriteUsesDeclarations() {
		$this->_stubFile->expects($this->once())->method('writeUsesDeclaration');

		$this->_classWriter->replacePlaceholders();
	}

	protected function GivenType() {
		$field1 = new Field(
			self::FIELD_1_NAME,
			self::FIELD_1_DESC,
			new TypeUsage(
				self::FIELD_1_TYPE_NAME,
				true,
				false,
				false
			),
			[]
		);

		$field2 = new Field(
			self::FIELD_2_NAME,
			self::FIELD_2_DESC,
			new TypeUsage(
				self::FIELD_2_TYPE_NAME,
				true,
				false,
				false
			),
			[]
		);

		return new Type(
			self::PRIMARY_TYPE_NAME,
			$this->_psr4Context->formatter,
			[$field1, $field2]
		);
	}
}