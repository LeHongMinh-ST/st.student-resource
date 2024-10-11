<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * @group Admin API
 *
 * APIs for admin
 *
 * @subgroup Major
 *
 * @subgroupDescription APIs for managing posts
 */
class MajorController extends Controller
{
    public function __construct(private readonly MajorService $majorService)
    {
    }
}
