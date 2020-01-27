<?php

namespace App\GraphQL\Support;

use App\GraphQL\Support\Admin;
use Rebing\GraphQL\Support\Mutation;

abstract class AdminMutation extends Mutation
{
    use Admin;
}