<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;


class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];


    protected function by($username)
    {
        $user = User::where('name', $username)->firstorFail();
        return $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {
        return $this->builder->has('replies', 0);
    }
}
