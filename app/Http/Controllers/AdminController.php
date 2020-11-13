<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Rules\ValidCurrentPassword;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function profile() {
        return $this->getView('admins.profile')->withAdmin(Auth::user());
    }

    public function updateProfile(Request $request, Admin $admin) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'current_password' => ['required_with:change_password', new ValidCurrentPassword($admin->password, $request->change_password ?? false)],
            'password' => 'required_with:change_password|confirmed'
        ],
        [
            'current_password.required_with' => 'Senha Atual não informada.',
            'password.required_with' => 'Nova senha não informada.',
            'password.confirmed' => 'Nova senha não confere com campo Confirmação.'
        ],
        [
            'name' => 'Nome',
            'email' => 'E-mail',
            'current_password' => 'Senha Atual',
            'password' => 'Senha'
        ]);

        try {
            $admin->name = $request->name;
            $admin->email = $request->email;
            if ($request->change_password) {
                $admin->password = bcrypt($request->password);
            }

            if ($admin->save()) {
                return response()->json(['redirect' => 'admin.index']);
            } else {
                throw new Exception('Ocorreu um erro ao salvar as alterações na Conta de Usuário.');
            }
        } catch (Exception $e) {

        }
    }
}
