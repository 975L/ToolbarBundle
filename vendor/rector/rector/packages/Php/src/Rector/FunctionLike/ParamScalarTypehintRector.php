<?php declare(strict_types=1);

namespace Rector\Php\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\NodeTypeResolver\Php\ParamTypeInfo;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class ParamScalarTypehintRector extends AbstractScalarTypehintRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change @param types to scalar typehints if not a BC-break', [
            new CodeSample(
                <<<'CODE_SAMPLE'
<?php

class ParentClass
{
    /**
     * @param int $number
     */
    public function keep($number)
    {
    }
}

final class ChildClass extends ParentClass
{
    /**
     * @param int $number
     */
    public function keep($number)
    {
    }

    /**
     * @param int $number
     */
    public function change($number)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
<?php

class ParentClass
{
    /**
     * @param int $number
     */
    public function keep($number)
    {
    }
}

final class ChildClass extends ParentClass
{
    /**
     * @param int $number
     */
    public function keep($number)
    {
    }

    /**
     * @param int $number
     */
    public function change(int $number)
    {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (empty($node->params)) {
            return null;
        }

        $paramTagInfos = $this->docBlockAnalyzer->getParamTypeInfos($node);

        // no tags, nothing to complete here
        if ($paramTagInfos === []) {
            return null;
        }

        foreach ($node->params as $position => $paramNode) {
            // skip variadics
            if ($paramNode->variadic) {
                continue;
            }

            // already set → skip
            $hasNewType = false;
            if ($paramNode->type) {
                $hasNewType = $paramNode->type->getAttribute(self::HAS_NEW_INHERITED_TYPE, false);
                if ($hasNewType === false) {
                    continue;
                }
            }

            $paramNodeName = $this->getName($paramNode->var);

            // no info about it
            if (! isset($paramTagInfos[$paramNodeName])) {
                continue;
            }

            $paramTypeInfo = $paramTagInfos[$paramNodeName];

            if ($paramTypeInfo->isTypehintAble() === false) {
                continue;
            }

            if ($node instanceof ClassMethod && $this->isChangeVendorLockedIn($node, $position)) {
                continue;
            }

            if ($hasNewType) {
                // should override - is it subtype?
                $possibleOverrideNewReturnType = $paramTypeInfo->getTypeNode();
                if ($this->isSubtypeOf($possibleOverrideNewReturnType, $paramNode->type)) {
                    // allow override
                    $paramNode->type = $paramTypeInfo->getTypeNode();
                }
            } else {
                $paramNode->type = $paramTypeInfo->getTypeNode();
            }

            /** @var string $methodName */
            $methodName = $node->getAttribute(Attribute::METHOD_NAME);

            /** @var string $className */
            $className = $node->getAttribute(Attribute::CLASS_NAME);

            // inherit typehint to all children
            if ($node instanceof ClassMethod) {
                $childrenClassLikes = $this->classLikeNodeCollector->findClassesAndInterfacesByType($className);

                // update their methods as well
                foreach ($childrenClassLikes as $childClassLike) {
                    if ($childClassLike instanceof Class_) {
                        $usedTraits = $this->classLikeNodeCollector->findUsedTraitsInClass($childClassLike);

                        foreach ($usedTraits as $trait) {
                            $this->addParamTypeToMethod($trait, $methodName, $position, $node, $paramTypeInfo);
                        }
                    }

                    $this->addParamTypeToMethod($childClassLike, $methodName, $position, $node, $paramTypeInfo);
                }
            }
        }

        return $node;
    }

    private function addParamTypeToMethod(
        ClassLike $classLikeNode,
        string $methodName,
        int $position,
        Node $node,
        ParamTypeInfo $paramTypeInfo
    ): void {
        $classMethod = $classLikeNode->getMethod($methodName);
        if ($classMethod === null) {
            return;
        }

        if (! isset($classMethod->params[$position])) {
            return;
        }

        $paramNode = $classMethod->params[$position];

        // already has a type
        if ($paramNode->type !== null) {
            return;
        }

        $paramNode->type = $this->resolveChildType($paramTypeInfo, $node, $classMethod);

        // let the method know it was changed now
        $paramNode->type->setAttribute(self::HAS_NEW_INHERITED_TYPE, true);

        $this->notifyNodeChangeFileInfo($paramNode);
    }
}
