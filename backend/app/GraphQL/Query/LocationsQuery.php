<?php

namespace App\GraphQL\Query;

use DB;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class LocationsQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'Locations'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('location'));
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $sql = <<<SQL
            SELECT
            `acct_id`, `acct`, `parent`
            FROM
                (`costcenter_tree`)

SQL;
        return DB::connection('tenant')->select($sql);
    }
}