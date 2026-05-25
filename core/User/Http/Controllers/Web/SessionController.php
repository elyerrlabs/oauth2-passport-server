<?php

namespace Core\User\Http\Controllers\Web;

use App\Http\Controllers\WebController;
use App\Services\SessionService;

final class SessionController extends WebController
{
    public function __construct(protected SessionService $sessionService)
    {
        parent::__construct();
    }

    /**
     * destroy
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->sessionService->delete($id);

        return redirect()->back()->with('success', __('Session deleted successully'));
    }
}
