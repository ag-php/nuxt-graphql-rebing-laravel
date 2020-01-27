<?php

namespace App\GraphQL\Support;

use App\GraphQL\Support\Admin;
use Rebing\GraphQL\Support\Query;

abstract class AdminQuery extends Query
{
    use Admin;
}