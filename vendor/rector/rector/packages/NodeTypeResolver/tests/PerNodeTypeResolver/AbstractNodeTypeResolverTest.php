<?php declare(strict_types=1);

namespace Rector\NodeTypeResolver\Tests\PerNodeTypeResolver;

use PhpParser\Node;
use Rector\Configuration\Option;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Parser\Parser;
use Rector\Tests\AbstractContainerAwareTestCase;
use Symplify\PackageBuilder\FileSystem\SmartFileInfo;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

abstract class AbstractNodeTypeResolverTest extends AbstractContainerAwareTestCase
{
    /**
     * @var NodeTypeResolver
     */
    protected $nodeTypeResolver;

    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var NodeScopeAndMetadataDecorator
     */
    private $nodeScopeAndMetadataDecorator;

    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    protected function setUp(): void
    {
        $this->betterNodeFinder = $this->container->get(BetterNodeFinder::class);
        $this->parameterProvider = $this->container->get(ParameterProvider::class);
        $this->nodeTypeResolver = $this->container->get(NodeTypeResolver::class);
        $this->parser = $this->container->get(Parser::class);
        $this->nodeScopeAndMetadataDecorator = $this->container->get(NodeScopeAndMetadataDecorator::class);
    }

    /**
     * @return Node[]
     */
    protected function getNodesForFileOfType(string $file, string $type): array
    {
        $nodes = $this->getNodesForFile($file);

        return $this->betterNodeFinder->findInstanceOf($nodes, $type);
    }

    /**
     * @return Node[]
     */
    private function getNodesForFile(string $file): array
    {
        $smartFileInfo = new SmartFileInfo($file);

        $this->parameterProvider->changeParameter(Option::SOURCE, [$file]);

        $nodes = $this->parser->parseFile($smartFileInfo->getRealPath());

        return $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($nodes, $smartFileInfo->getRealPath());
    }
}
