<?php declare(strict_types=1);

namespace Rector\Php\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see https://www.phpclasses.org/blog/package/9199/post/3-Smoothly-Migrate-your-PHP-Code-using-the-Old-MySQL-extension-to-MySQLi.html
 */
final class MysqlFuncCallToMysqliRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Converts more complex mysql functions to mysqli',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
mysql_drop_db($database);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
mysqli_query('DROP DATABASE ' . $database);
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->isName($node, 'mysql_drop_db')) {
            return $this->processMysqlDropDb($node);
        }

        if ($this->isName($node, 'mysql_list_dbs')) {
            $node->name = new Name('mysqli_query');
            $node->args[0] = new Arg(new String_('SHOW DATABASES'));
        }

        if ($this->isName($node, 'mysql_list_fields')) {
            $node->name = new Name('mysqli_query');
            $node->args[0]->value = $this->joinStringWithNode('SHOW COLUMNS FROM', $node->args[1]->value);

            unset($node->args[1]);
        }

        if ($this->isName($node, 'mysql_list_tables')) {
            $node->name = new Name('mysqli_query');
            $node->args[0]->value = $this->joinStringWithNode('SHOW TABLES FROM', $node->args[0]->value);
        }

        return $node;
    }

    private function processMysqlDropDb(FuncCall $funcCallNode): FuncCall
    {
        $funcCallNode->name = new Name('mysqli_query');
        $funcCallNode->args[0]->value = $this->joinStringWithNode('DROP DATABASE', $funcCallNode->args[0]->value);

        return $funcCallNode;
    }

    private function joinStringWithNode(string $string, Expr $node): Expr
    {
        if ($node instanceof String_) {
            return new String_($string . ' ' . $node->value);
        }

        return new Concat(new String_($string . ' '), $node);
    }
}
