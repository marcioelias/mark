<?php

namespace App\Http\Controllers;

use App\Mail\NewUserCreated;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class WebhookController extends Controller
{
    public function systemWebhook(Request $request) {
        if ($request->chave_unica != '82e98fd17562ae451ba4f9e3b9c2eab6') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $plan = Plan::where('marketplace_code', $request->produto['chave'])->first();
        if (!$plan) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $user = User::where('email', $request->comprador['email'])->get();
        if (!$user->count()) {
            /* novo usuário, trata da criação do mesmo no sistema */

        }
        //Log::debug($plan);
        /* Log::debug($request->all()); */
        $this->createNewUser($request, $plan);

        return response()->json(['message' => 'Success'], 200);
    }

    public function createNewUser(Request $request, Plan $plan) {
        $tempPassword = 'password';//Str::random(8);
        $user = new User([
            'name' => $request->comprador['nome'],
            'email' => $request->comprador['email'],
            'phone_number' => $request->comprador['telefone'],
            'plan_id' => $plan->id,
            'password' => bcrypt($tempPassword),
            'active' => $request->assinatura['status'] === 'Ativa'
        ]);

        if ($user->save()) {
            Log::debug($user);
        }

        event(new Registered($user));
        //$this->sendNewUserAccountEmail($user, $tempPassword);
    }

    public function sendNewUserAccountEmail(User $user, string $password) {
        Log::debug($password);
        Mail::to($user)->send(new NewUserCreated($user, $password));
    }
}
