<?php

namespace App\Http\Controllers;

use App\Models\ActionType;
use Illuminate\Http\Request;

class ActionTypeController extends Controller
{
    public function getActionTypesJson() {
        return response()->json(ActionType::orderBy('action_type_description', 'asc')->get());
    }
}
