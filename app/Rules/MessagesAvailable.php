<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MessagesAvailable implements Rule
{
    private $user;
    private $activatedAt;
    private $actionType;
    private $total;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(String $actionType, int $total)
    {
        $this->user = Auth::user();
        $this->activatedAt = $this->user->activated_at ?? now();
        $this->actionType = $actionType;
        $this->total = $total;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $available = $this->getMessagesAvailable();
        $used = $this->getSmsUsed();
        return $available > 0 && (($available - $used) < (int) $this->total);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Saldo de Mensagens excede o necessário para rodar essa Ação de Marketing.';
    }

    /**
     * Get number of messages available at plan
     *
     * @return integer
     */
    private function getMessagesAvailable(): int {
        return Auth::user()->plan
                           ->features
                           ->firstWhere('action_type_id', $this->actionType)
                           ->pivot
                           ->limit ?? 0;
    }

    /**
     * Get the number os SMS messages sent at current period of the plan usage
     *
     * @return integer
     */
    private function getSmsUsed(): int {
        return $this->user->actions
                          ->where('executed_at', '>=', $this->activatedAt)
                          ->where('action_type_id', '=', $this->actionType)
                          ->count() ?? 0;
    }
}
