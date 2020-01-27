<?php

namespace App\GraphQL\Support;

use App\GraphQL\Support\Tenancy;
use Rebing\GraphQL\Support\Query;

abstract class AuthenticatedQuery extends Query
{
    use Tenancy;
}
