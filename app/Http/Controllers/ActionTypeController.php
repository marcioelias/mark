<?php

namespace App\Http\Controllers;

use App\Models\ActionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionTypeController extends Controller
{
    public function getActionTypesJson() {
        $featuresPlan = Auth::user()->plan->enabledFeatures()->whereHas('actionType')->get();
        //return response()->json($featuresPlan);
        foreach ($featuresPlan as $feature) {
            $featuredActionTypes[] = $feature->actionType;
        }

        $actionTypes = ActionType::doesntHave('feature')->get()->toArray();

        return response()->json(array_merge($featuredActionTypes, $actionTypes));
    }
}
