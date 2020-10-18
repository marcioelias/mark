<?php

namespace App\Http\Controllers;

use App\Contracts\Postback;
use App\Mail\NewUserCreated;
use App\Models\Plan;
use App\Models\TempPassword;
use App\Models\User;
use App\Traits\PostbackTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class WebhookMonetizzeController extends Controller implements Postback
{

    use PostbackTrait;

    public function getPostbackKeyFieldName() {
        return 'chave_unica';
    }

    /**
     * Process the received postback
     *
     * @param array $postback
     * @return void
     */
    public function handle(array $postback) {
        $plan = $this->getPlan($postback);
        if (!$plan) {
            return $this->response(404, 'Product not found');
        }

        $user = $this->getUser($postback);

        if (!$user) {
            /* novo usuário, trata da criação do mesmo no sistema */
            $this->createNewUser($postback, $plan);
        } else {
            $this->updateAssinatura($user, $postback);
        }

        //101=ASSINATURA_ATIVA, 102=ASSINATURA_INADIMPLENTE, 103=ASSINATURA_CANCELADA, 104=ASSINATURA_AGUARDANDO_PAGAMENTO

        return response()->json(['message' => 'Success'], 200);
    }

    public function getPlan(array $postback) {
        return Plan::where('marketplace_code', $postback['produto']['chave'] ?? '')->first();
    }

    public function getUser(array $postback) {
        return User::where('email', $postback['comprador']['email'] ?? '')->first();
    }

    public function updateAssinatura(User $user, array $postback) {
        $user->active = (int) $postback['tipoEvento']['codigo'] === (int) 101;
        $user->activated_at = Carbon::now();
        $user->save();
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Plan $plan
     * @return void
     */
    public function createNewUser(array $postback, Plan $plan) {
        $tmpPass = new TempPassword();
        $user = new User([
            'name' => $postback['comprador']['nome'],
            'email' => $postback['comprador']['email'],
            'phone_number' => $postback['comprador']['telefone'],
            'plan_id' => $plan['id'],
            'password' => bcrypt($tmpPass['temp_password']),
        ]);

        if ($postback['assinatura']['status'] === 'Ativa') {
            $user->active = true;
            $user->activated_at = Carbon::now();
        }

        if ($user->save()) {
            $user->temp_password()->save($tmpPass);
        }

        event(new Registered($user));
    }
}
