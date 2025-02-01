<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface EloquentFilterContract
{
	public function applyEloquent(Builder $model): Builder;
	
}
