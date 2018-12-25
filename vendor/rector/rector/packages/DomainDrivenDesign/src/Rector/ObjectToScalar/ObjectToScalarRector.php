<?php declare(strict_types=1);

namespace Rector\DomainDrivenDesign\Rector\ObjectToScalar;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Property;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class ObjectToScalarRector extends AbstractObjectToScalarRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove values objects and use directly the value.', [
            new ConfiguredCodeSample(
<<<'CODE_SAMPLE'
$name = new ValueObject("name");

function someFunction(ValueObject $name): ?ValueObject {
}
CODE_SAMPLE
                ,
<<<'CODE_SAMPLE'
$name = "name";

function someFunction(string $name): ?string {
}
CODE_SAMPLE
                ,
                [
                    '$valueObjectsToSimpleTypes' => [
                        'ValueObject' => 'string',
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [New_::class, Property::class, Name::class, NullableType::class];
    }

    /**
     * @param New_|Property|Name|NullableType $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof New_ && $this->processNewCandidate($node)) {
            return $node->args[0];
        }

        if ($node instanceof Property && $this->isTypes($node, array_keys($this->valueObjectsToSimpleTypes))) {
            return $this->refactorProperty($node);
        }

        if ($node instanceof Name) {
            $parentNode = $node->getAttribute(Attribute::PARENT_NODE);
            if (! $parentNode instanceof Param) {
                return null;
            }

            return $this->refactorName($node);
        }

        if ($node instanceof NullableType) {
            return $this->refactorNullableType($node);
        }

        return null;
    }

    private function processNewCandidate(New_ $newNode): bool
    {
        if (count($newNode->args) !== 1) {
            return false;
        }

        return $this->isTypes($newNode->class, array_keys($this->valueObjectsToSimpleTypes));
    }

    private function refactorProperty(Property $propertyNode): Property
    {
        $newType = $this->matchNewType($propertyNode);
        if ($newType === null) {
            return $propertyNode;
        }

        return $propertyNode;
    }

    private function refactorName(Node $nameNode): ?Name
    {
        $newType = $this->matchNewType($nameNode);
        if ($newType === null) {
            return null;
        }

        return new Name($newType);
    }

    private function refactorNullableType(NullableType $nullableTypeNode): NullableType
    {
        $newType = $this->matchNewType($nullableTypeNode->type);
        if (! $newType) {
            return $nullableTypeNode;
        }

        return new NullableType($newType);
    }
}
