<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\TempPassword;
use App\Models\User;
use App\Traits\LayoutConfigTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public $fields = array(
        'name' => 'Nome',
        'email' => 'E-mail',
        'plan_name' => 'Plano',
        'active' => ['label' => 'Ativo', 'type' => 'bool']
    );

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
}
