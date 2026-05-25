<?php

namespace App\Services;

use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

final class SessionService
{
    public function __construct(protected SessionRepository $sessionRepository)
    {
    }

    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Setting\Session>|\Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Builder<\App\Models\Setting\Session>>
     */
    public function search(Request $request)
    {
        $query = $this->sessionRepository->query();

        $query->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->input('user_id')));

        return $query;
    }

    /**
     * Search for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Setting\Session>|\Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Builder<\App\Models\Setting\Session>>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->sessionRepository->query();

        $query->where('user_id', $request->user()->id);

        return $query;
    }


    /**
     * Find
     * @param string $id
     */
    public function find(string $id)
    {
        return $this->sessionRepository->find($id);
    }

    /**
     * Delete
     * @param string $id
     * @return \App\Models\Setting\Session|\App\Repositories\TValue|\stdClass|null
     */
    public function delete(string $id)
    {
        $session = $this->sessionRepository->find($id);

        if (!empty($session)) {
            $session->delete();
        }

        return $session;
    }

    /**
     * Delete sessions
     * @return void
     */
    public function closeAllSession()
    {
        $this->sessionRepository->query()
            ->where('id', '!=', session()->getId())
            ->where('user_id', request()->user()->id)
            ->delete();
    }
}
