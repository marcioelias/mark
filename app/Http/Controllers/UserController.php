<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\TempPassword;
use App\Models\User;
use App\Rules\cpfCnpj;
use App\Rules\ValidCurrentPassword;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'name' => 'Nome',
            'email' => 'E-mail',
            'plan_name' => 'Plano',
            'active' => ['label' => 'Ativo', 'type' => 'bool']
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'name'=>"Clientes"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $users = User::select(['users.*', 'plans.plan_name'])
                        ->join('plans', 'users.plan_id', '=', 'plans.id', 'left outer')
                        ->where('name', 'like', "%$request->searchField%")
                        ->orWhere('email', 'like', "%$request->searchField%")
                        ->orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $users = User::select(['users.*', 'plans.plan_name'])
                        ->join('plans', 'users.plan_id', '=', 'plans.id', 'left outer')
                        ->orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('users.index')
                    ->withUsers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link'=>"/admin/user",
                'name'=>"Clientes"
            ],
            [
                'name'=>"Novo"
            ]
        ];

        $plans = Plan::orderBy('plan_name', 'asc')->get();
        return $this->getView('users.create')->withPlans($plans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'customer_code' => 'required|unique:users',
            'email' => 'required|unique:users',
            'phone_number' => 'required',
            'plan_id' => 'required',
            'password' => 'required|min:6|max:30|confirmed'
        ]);

        $user = new User($request->all());
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            $tempPassword = new TempPassword;
            $tempPassword->temp_password = $request->password;
            $user->temp_password()->save($tempPassword);
        }

        event(new Registered($user));
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link'=>"/admin/user",
                'name'=>"Clientes"
            ],
            [
                'name'=>"Alterar"
            ]
        ];

        $plans = Plan::orderBy('plan_name', 'asc')->get();
        return $this->getView('users.edit')->withPlans($plans)->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'customer_code' => "required|unique:users,customer_code,$user->id,id",
            'phone_number' => 'required',
            'plan_id' => 'required',
        ]);

        $user->fill($request->all());
        $user->active = ($request->active ?? false) ? true : false;
        return response()->json($user->save());
    }

    public function updateProfile(Request $request, User $user) {

        $this->validate($request, [
            'name' => 'required',
            'doc_number' => ['required', new cpfCnpj],
            'phone_number' => 'required',
            'zip_code' => 'required',
            'street_name' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required:',
            'current_password' => ['required_with:change_password', new ValidCurrentPassword($user->password, $request->change_password ?? false)],
            'password' => 'required_with:change_password|confirmed'
        ],
        [
            'current_password.required_with' => 'Senha Atual não informada.',
            'password.required_with' => 'Nova senha não informada.',
            'password.confirmed' => 'Nova senha não confere com campo Confirmação.'
        ],
        [
            'name' => 'Nome',
            'doc_number' => 'CPF',
            'phone_number' => 'Telefone',
            'zip_code' => 'CEP',
            'street_name' => 'Endereço/Logradouro',
            'neighborhood' => 'Bairro',
            'city' => 'Cidade',
            'state' => 'Estado',
            'current_password' => 'Senha Atual',
            'password' => 'Senha'
        ]);

        try {
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->doc_number = $request->doc_number;
            $user->phone_number = $request->phone_number;
            $user->zip_code = $request->zip_code;
            $user->street_name = $request->street_name;
            $user->street_number = $request->street_number;
            $user->neighborhood = $request->neighborhood;
            $user->city = $request->city;
            $user->state = $request->state;
            if ($request->change_password) {
                $user->password = bcrypt($request->password);
            }

            if ($user->save()) {
                return response()->json(['redirect' => 'index']);
            } else {
                throw new Exception('Ocorreu um erro ao salvar as alterações na Conta de Usuário.');
            }
        } catch (Exception $e) {

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return response()->json($user->delete());
    }

    public function profile() {
        return $this->getView('users.profile')
                    ->withUser(Auth::user());
    }
}
