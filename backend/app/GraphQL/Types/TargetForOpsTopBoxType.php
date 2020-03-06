<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TargetForOpsTopBoxType extends GraphQLType
{
  protected $attributes = [
    'name'          => 'targetForOpsTopBox',
    'description'   => 'data for target on  box',
  ];

  public function fields(): array
  {
    return [
      'store' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The name of location',
      ],
      'item' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The name of the category',
      ],
      'amount' => [
        'type' =>  Type::float(),
        'description' => 'amount for the item',
      ],
    ];
  }
}
