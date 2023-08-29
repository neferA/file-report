<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\waranty;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:crud-usuario', ['only' => ['index']]);
        $this->middleware('permission:crud-usuario', ['only' => ['create', 'store']]);
        $this->middleware('permission:crud-usuario', ['only' => ['edit', 'update']]);
        $this->middleware('permission:crud-usuario', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10); //Mostrar 5 registros por página
        return view('users.index', compact('users'));
    }

    public function home()
    {
        $now = now();
        $alarms = [];
    
        // Obtener las garantías que están a punto de expirar
        $expiringWarranties = Waranty::whereDate('fecha_final', '>=', $now)
            ->whereDate('fecha_final', '<=', $now->addDays(13)) // Cambiar a 13 días si es naranja
            ->get();
    
        foreach ($expiringWarranties as $warranty) {
            $alarms[] = [
                'warranty' => $warranty,
                'color' => ($now->diffInDays($warranty->fecha_final) <= 11) ? 'red' : 'orange'
            ];
        }
    
        return view('users.home', compact('alarms'));
    }

    public function financiers()
    {
        return view('financiadoras.index');
    }
    public function waranty()
    {
        return view('garantias.index');
    }
    public function executor()
    {
        return view('ejecutoras.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function adminlte_profile_url()
    {
        $user = auth()->user();
        $role = $user->roles->first();
        return view('users.account', compact('user', 'role'));
    }
    public function account_details()
    {
        $user = auth()->user();
        $role = $user->roles->first();
        return view('users.account', compact('user', 'role'));
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name');
        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
        ]);
        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index');
    }
}
