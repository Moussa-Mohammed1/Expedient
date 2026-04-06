<?php

namespace App\View\Components;

use App\Models\Coach;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CoachBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Coach $coach
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->coach->load('latestVerification');
        return view('components.coach-badge');
    }
}
