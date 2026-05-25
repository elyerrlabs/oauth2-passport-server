<?php

namespace App\Repositories;

use App\Models\Setting\Session;

final class SessionRepository
{

    public function __construct(protected Session $session)
    {

    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Session>|\Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Builder<Session>>
     */
    public function query()
    {
        return $this->session->query()->with(['user']);
    }

    /**
     * Find resource
     * @param string $id
     * @return Session|TValue|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }
}
