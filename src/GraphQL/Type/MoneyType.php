<?php

namespace App\GraphQL\Type;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use Money\Money;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use GraphQL\Type\Definition\ScalarType;

class MoneyType extends ScalarType implements AliasedInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getAliases()
    {
        return ['Money', 'MoneyType'];
    }
    // ...

    /**
     * Serializes an internal value to include in a response.
     *
     * @param Money $value
     * @return mixed
     * @throws Error
     */
    public function serialize($value)
    {
        return $value->absolute();
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * In the case of an invalid value this method must throw an Exception
     *
     * @param mixed $value
     * @return mixed
     * @throws Error
     */
    public function parseValue($value)
    {
        return 1;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node $valueNode
     * @param array|null $variables
     * @return mixed
     * @throws \Exception
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        return 1;
    }
}