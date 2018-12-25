<?php declare(strict_types=1);

namespace Rector\Rector;

use Nette\Utils\Strings;
use PhpParser\Node;
use Rector\PhpParser\Node\Resolver\NameResolver;

/**
 * This could be part of @see AbstractRector, but decopuling to trait
 * makes clear what code has 1 purpose.
 */
trait NameResolverTrait
{
    /**
     * @var NameResolver
     */
    private $nameResolver;

    /**
     * @required
     */
    public function setNameResolver(NameResolver $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }

    public function isName(Node $node, string $name): bool
    {
        return $this->getName($node) === $name;
    }

    public function nameStartsWith(Node $node, string $name): bool
    {
        return Strings::startsWith($this->getName($node), $name);
    }

    public function isNameInsensitive(Node $node, string $name): bool
    {
        return strtolower((string) $this->getName($node)) === strtolower($name);
    }

    /**
     * @param string[] $names
     */
    public function isNames(Node $node, array $names): bool
    {
        return in_array($this->getName($node), $names, true);
    }

    public function getName(Node $node): ?string
    {
        return $this->nameResolver->resolve($node);
    }
}
