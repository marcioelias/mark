<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function getPaymentTypesJson(): JsonResponse
    {
        try {
            return response()->json(PaymentType::orderBy('payment_type', 'ASC')->get());
        } catch (Exception $e) {
            return response()->json([]);
        }
    }
}
