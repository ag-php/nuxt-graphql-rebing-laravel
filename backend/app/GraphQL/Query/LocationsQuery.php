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
        return Type::listOf(Type::string());
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $data = DB::connection('tenant')->select("SELECT `acct` FROM `costcenter_tree` WHERE `parent` <> 0;");

        $locations = [];

        foreach($data as $r) {
            $locations[] = $r->acct;
        }

        return $locations;
    }
}