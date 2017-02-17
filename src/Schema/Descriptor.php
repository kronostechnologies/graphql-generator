<?php


namespace GraphQLGen\Schema;


use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class Descriptor {
	/**
	 * @var OutputInterface
	 */
	protected $_consoleOutput;
	protected $_descriptionsEnabled;

	/**
	 * @param OutputInterface $console_output
	 * @param bool $descriptions_enabled
	 */
	public function __construct($console_output, $descriptions_enabled = true) {
		$this->_consoleOutput = $console_output;
		$this->_descriptionsEnabled = $descriptions_enabled;

		$this->setupStyles();
	}

	protected function setupStyles() {
		$interfaceDefinitionStyle = new OutputFormatterStyle('white', null, ['bold']);
		$enumDefinitionStyle = new OutputFormatterStyle('green', null, ['bold']);
		$scalarDefinitionStyle = new OutputFormatterStyle('magenta', null, ['bold']);
		$inputDefinitionStyle = new OutputFormatterStyle('cyan', null, ['bold']);
		$typeDefinitionStyle = new OutputFormatterStyle('red', null, ['bold']);
		$typeUsageStyle = new OutputFormatterStyle('yellow', null, ['bold']);

		$this->_consoleOutput->getFormatter()->setStyle('interface', $interfaceDefinitionStyle);
		$this->_consoleOutput->getFormatter()->setStyle('enum', $enumDefinitionStyle);
		$this->_consoleOutput->getFormatter()->setStyle('scalar', $scalarDefinitionStyle);
		$this->_consoleOutput->getFormatter()->setStyle('input', $inputDefinitionStyle);
		$this->_consoleOutput->getFormatter()->setStyle('typedef', $typeDefinitionStyle);
		$this->_consoleOutput->getFormatter()->setStyle('typeusage', $typeUsageStyle);
	}

	/**
	 * @param DocumentNode $ast
	 */
	public function describeAST($ast) {
		foreach($ast->definitions as $astNode) {
			$this->describeNode($astNode);
		}
	}

	/**
	 * @param Node $node
	 */
	protected function describeNode($node) {
		switch(get_class($node)) {
			case ScalarTypeDefinitionNode::class:
				$this->describeScalarTypeNode($node);
				break;
			case EnumTypeDefinitionNode::class:
				$this->describeEnumTypeNode($node);
				break;
			case InterfaceTypeDefinitionNode::class:
				$this->describeInterfaceTypeNode($node);
				break;
			case ObjectTypeDefinitionNode::class:
				$this->describeObjectTypeNode($node);
				break;
			case InputValueDefinitionNode::class:
			case FieldDefinitionNode::class:
				$this->describeFieldType($node);
				break;
			case InputObjectTypeDefinitionNode::class:
				$this->describeInputType($node);
				break;
		}
	}

	/**
	 * @param ScalarTypeDefinitionNode $node
	 */
	protected function describeScalarTypeNode($node) {
		$this->_consoleOutput->writeln("<scalar>Scalar</scalar> {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
	}

	protected function writeDescriptionText($node) {
		if($this->_descriptionsEnabled) {
			$this->_consoleOutput->writeln("\t\t{$this->getDescriptionText($node->description)}");
		}
	}

	protected function getDescriptionText($description) {
		return $description ? str_replace("\n", "", trim($description)) : "<error>(No description)</error>";
	}

	/**
	 * @param EnumTypeDefinitionNode $node
	 */
	protected function describeEnumTypeNode($node) {
		$this->_consoleOutput->writeln("<enum>Enum</enum> {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
		$this->_consoleOutput->writeln("\tValues:");
		foreach($node->values as $enumValueNode) {
			$this->describeEnumValueNode($enumValueNode);
		}
		$this->_consoleOutput->writeln("");
	}

	/**
	 * @param EnumValueDefinitionNode $node
	 */
	protected function describeEnumValueNode($node) {
		$this->_consoleOutput->writeln("\t- {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
	}

	/**
	 * @param InterfaceTypeDefinitionNode $node
	 */
	protected function describeInterfaceTypeNode($node) {
		$this->_consoleOutput->writeln("<interface>Interface</interface> {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
		foreach($node->fields as $interfaceFieldNode) {
			$this->describeNode($interfaceFieldNode);
		}
	}

	/**
	 * @param InterfaceTypeDefinitionNode $node
	 */
	protected function describeObjectTypeNode($node) {
		$this->_consoleOutput->writeln("<typedef>Type</typedef> {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
		foreach($node->fields as $interfaceFieldNode) {
			$this->describeNode($interfaceFieldNode);
		}
	}

	/**
	 * @param FieldDefinitionNode|InputValueDefinitionNode $node
	 */
	protected function describeFieldType($node) {
		$subType = $node->type;

		$this->_consoleOutput->writeln("\t- {$node->name->value}");
		$this->_consoleOutput->writeln("\t\tType: {$this->getResolvedNameTypeDescription($subType)}");
		$this->describeFieldTypeArguments(isset($node->arguments) ? $node->arguments : null);
		$this->_consoleOutput->writeln("");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
	}

	protected function getResolvedNameTypeDescription($baseType) {
		if(get_class($baseType) === NonNullTypeNode::class) {
			$inList = get_class($baseType->type) === ListTypeNode::class;
			$isNullableList = !$inList;
			$isNullableObject = $inList ? $baseType->type->type !== NonNullTypeNode::class : false;
		}
		else {
			$inList = get_class($baseType) === ListTypeNode::class;
			$isNullableList = true;
			$isNullableObject = $inList ? $baseType->type === NonNullTypeNode::class : true;
		}

		// Finds name node
		$nameNode = $baseType;
		while(get_class($nameNode) !== NamedTypeNode::class) {
			$nameNode = $nameNode->type;
		}

		$type = "<typeusage>" . $nameNode->name->value . ($isNullableObject ? "?" : "!") . "</typeusage>";
		if($inList) {
			$type = "[" . $type . "]" . ($isNullableList ? "?" : "!");
		}

		return $type;
	}

	protected function describeFieldTypeArguments($arguments) {
		if(!empty($arguments)) {
			$this->_consoleOutput->writeln("\t\tArguments:");
			foreach($arguments as $argument) {
				$this->_consoleOutput->writeln("\t\t- {$argument->name->value}: {$this->getResolvedNameTypeDescription($argument->type)}");
			}
		}
	}

	/**
	 * @param InterfaceTypeDefinitionNode $node
	 */
	protected function describeInputType($node) {
		$this->_consoleOutput->writeln("<input>Input</input> {$node->name->value}");
		$this->writeDescriptionText($node);
		$this->_consoleOutput->writeln("");
		foreach($node->fields as $inputFieldNode) {
			$this->describeNode($inputFieldNode);
		}
	}
}