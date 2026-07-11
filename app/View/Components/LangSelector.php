<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LangSelector extends Component
{

    /**
     * Lang
     * @var string
     */
    public string $lang;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = request()->user() ?? null;

        $locale = request()->filled('lang') ? request()->lang : substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        if (!empty($user)) {
            $locale = $user->lang ?? "en";
        }

        $this->lang = $locale;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.lang-selector');
    }
}
