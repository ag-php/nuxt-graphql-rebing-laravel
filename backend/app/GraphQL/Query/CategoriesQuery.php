<?php

namespace App\GraphQL\Query;

use DB;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class CategoriesQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'Categories'
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('category'));
  }

  public function args(): array
  {
    return [];
  }

  public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    $sql = <<<SQL
        SELECT `id`, `item`
        FROM `graph_setup` ORDER BY `id` ASC
SQL;

    $data = DB::connection('tenant')->select($sql);

    $categories = [];

    foreach ($data as $r) {
      $name = str_replace('CGS ', '', trim(preg_replace('/[0-9]+/', '', $r->item)));
      $categories[] = [
        'id' => $r->id,
        'name' => $name
      ];
    }

    return $categories;
  }
}
