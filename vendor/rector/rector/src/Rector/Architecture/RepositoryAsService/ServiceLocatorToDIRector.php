<?php declare(strict_types=1);

namespace Rector\Rector\Architecture\RepositoryAsService;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Bridge\Contract\DoctrineEntityAndRepositoryMapperInterface;
use Rector\Exception\Bridge\RectorProviderException;
use Rector\Exception\ShouldNotHappenException;
use Rector\Naming\PropertyNaming;
use Rector\NodeTypeResolver\Node\Attribute;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use function Safe\sprintf;

final class ServiceLocatorToDIRector extends AbstractRector
{
    /**
     * @var DoctrineEntityAndRepositoryMapperInterface
     */
    private $doctrineEntityAndRepositoryMapper;

    /**
     * @var PropertyNaming
     */
    private $propertyNaming;

    public function __construct(
        DoctrineEntityAndRepositoryMapperInterface $doctrineEntityAndRepositoryMapper,
        PropertyNaming $propertyNaming
    ) {
        $this->doctrineEntityAndRepositoryMapper = $doctrineEntityAndRepositoryMapper;
        $this->propertyNaming = $propertyNaming;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns "$this->getRepository()" in Symfony Controller to constructor injection and private property access.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class ProductController extends Controller
{
    public function someAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getRepository('SomethingBundle:Product')->findSomething(...);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function someAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->productRepository->findSomething(...);
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'getRepository')) {
            return null;
        }

        $className = $node->getAttribute(Attribute::CLASS_NAME);
        if ($className === null) {
            return null;
        }

        /** @var MethodCall $methodCallNode */
        $methodCallNode = $node;
        if (count($methodCallNode->args) !== 1) {
            return null;
        }

        if ($methodCallNode->args[0]->value instanceof String_) {
            /** @var String_ $string */
            $string = $methodCallNode->args[0]->value;

            // is alias
            if (Strings::contains($string->value, ':')) {
                return null;
            }
        }

        if (Strings::endsWith($className, 'Repository')) {
            return null;
        }

        $repositoryFqn = $this->repositoryFqn($node);

        $this->addPropertyToClass(
            $node->getAttribute(Attribute::CLASS_NODE),
            $repositoryFqn,
            $this->propertyNaming->fqnToVariableName($repositoryFqn)
        );

        return $this->createPropertyFetch('this', $this->propertyNaming->fqnToVariableName($repositoryFqn));
    }

    private function repositoryFqn(MethodCall $methodCallNode): string
    {
        $entityFqnOrAlias = $this->entityFqnOrAlias($methodCallNode);

        $repositoryClassName = $this->doctrineEntityAndRepositoryMapper->mapEntityToRepository($entityFqnOrAlias);

        if ($repositoryClassName !== null) {
            return $repositoryClassName;
        }

        throw new RectorProviderException(sprintf(
            'A repository was not provided for "%s" entity by your "%s" class.',
            $entityFqnOrAlias,
            get_class($this->doctrineEntityAndRepositoryMapper)
        ));
    }

    private function entityFqnOrAlias(MethodCall $methodCallNode): string
    {
        $repositoryArgument = $methodCallNode->args[0]->value;

        if ($repositoryArgument instanceof String_) {
            return $repositoryArgument->value;
        }

        if ($repositoryArgument instanceof ClassConstFetch && $repositoryArgument->class instanceof Name) {
            return $repositoryArgument->class->getAttribute(Attribute::RESOLVED_NAME)->toString();
        }

        throw new ShouldNotHappenException('Unable to resolve repository argument');
    }
}
