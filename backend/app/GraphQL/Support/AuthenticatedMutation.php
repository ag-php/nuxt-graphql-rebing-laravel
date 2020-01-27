<?php

namespace App\GraphQL\Support;

use App\GraphQL\Support\Tenancy;
use Rebing\GraphQL\Support\Mutation;

abstract class AuthenticatedMutation extends Mutation
{
    use Tenancy;
}