<?php

namespace App\Http\Middleware;

use App\Constants\FeatureTypes;
use App\Models\Feature;
use Closure;
use Illuminate\Support\Facades\Auth;

class PostbacksLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $postbacks = Auth::user()->plan->features->firstWhere('id', FeatureTypes::POSTBACKS)->pivot;

        if ($postbacks->enabled) {
            if ($postbacks->limit == 0) {

            }
        }
        return $next($request);
    }
}
