<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\waranty;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

use App\Events\WarrantyExpired;


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
        $users = User::paginate(5); //Mostrar 5 registros por página
        return view('users.index', compact('users'));
    }
    
    public function home(Request $request)
    {
        // Obtener las garantías que están a punto de expirar
        $expiringWarranties = Waranty::whereDate('fecha_final', '>=', now())
            ->whereDate('fecha_final', '<=', now()->addDays(13)) // Cambiar a 13 días si es naranja
            ->get();

        // Iterar a través de las garantías y manejar las alarmas
        foreach ($expiringWarranties as $warranty) {
            // Lógica para determinar si es una alarma roja o naranja
            $isRedAlarm = $this->isRedAlarm($warranty);
            $isOrangeAlarm = $this->isOrangeAlarm($warranty);
            $isBlackAlarm = $this->isBlackAlarm($warranty);

            // Crear una instancia de WarrantyExpired con los valores correctos
            $event = new WarrantyExpired($warranty, $isRedAlarm, $isOrangeAlarm, $isBlackAlarm);
            event($event); // Disparar el evento
        }

        // Obtener las alarmas para mostrar en la vista
        $alarms = $this->getAlarms();
        
           // Aplicar el filtro de orden si se ha seleccionado
        $orden = $request->input('orden');

        if ($orden === 'creacion_asc') {
            $alarms = $this->ordenarAlarmasPorFechaAsc($alarms);
        } elseif ($orden === 'creacion_desc') {
            $alarms = $this->ordenarAlarmasPorFechaDesc($alarms);
        }

        //paginación de alarmas
        list($redAlarmsPaginator, $orangeAlarmsPaginator, $blackAlarmsPaginator) = $this->paginateAlarms($alarms, $request);

        return view('users.home', compact('redAlarmsPaginator', 'orangeAlarmsPaginator', 'blackAlarmsPaginator', 'orden'));
    }

    private function ordenarAlarmasPorFechaAsc($alarms)
    {
        // Utiliza la función usort para ordenar las alarmas por fecha de forma ascendente
        usort($alarms, function ($a, $b) {
            return strtotime($a['warranty']->fecha_final) - strtotime($b['warranty']->fecha_final);
        });

        return $alarms;
    }

    private function ordenarAlarmasPorFechaDesc($alarms)
    {
        // Utiliza la función usort para ordenar las alarmas por fecha de forma descendente
        usort($alarms, function ($a, $b) {
            return strtotime($b['warranty']->fecha_final) - strtotime($a['warranty']->fecha_final);
        });

        return $alarms;
    }

    private function isBlackAlarm($warranty)
    {
        $daysRemaining = now()->diffInDays($warranty->fecha_final);
        return $daysRemaining === 0;
    }

    private function isRedAlarm($warranty)
    {
        $daysRemaining = now()->diffInDays($warranty->fecha_final);
        return ($daysRemaining <= 11 && $daysRemaining > 0) || round($daysRemaining) === 2;
    }

    private function isOrangeAlarm($warranty)
    {
        $daysRemaining = now()->diffInDays($warranty->fecha_final);
        $isRed = ($daysRemaining <= 11 && $daysRemaining > 0) || round($daysRemaining) === 2;
        return $daysRemaining <= 13 && $daysRemaining > 0 && !$isRed;
    }

    private function getAlarms()
    {
        $alarms = [];
        
        // Obtener las garantías que están a punto de expirar
        $expiringWarranties = Waranty::whereDate('fecha_final', '>=', now())
            ->whereDate('fecha_final', '<=', now()->addDays(13)) // Cambiar a 13 días si es naranja
            ->get();

        foreach ($expiringWarranties as $warranty) {
            // Lógica para determinar si es una alarma roja, naranja o negra
            $isRedAlarm = $this->isRedAlarm($warranty->fecha_final);
            $isOrangeAlarm = $this->isOrangeAlarm($warranty->fecha_final);
            $isBlackAlarm = $this->isBlackAlarm($warranty->fecha_final);

            if ($isRedAlarm || $isOrangeAlarm || $isBlackAlarm) {
                // Almacenar la alarma en el array asociativo usando el ID de la garantía como clave
                $alarms[$warranty->id] = [
                    'color' => $isRedAlarm ? 'red' : ($isOrangeAlarm ? 'orange' : 'black'),
                ];
            }
        }

        return $alarms;
    }
  
    private function paginateAlarms($alarms, Request $request)
    {
        // Convierte el array de alarmas en una colección
        $alarmsCollection = collect($alarms);
    
        // Número de alarmas por página para cada tipo de alarma
        $perPageRed = 3; // Número de alarmas rojas por página
        $perPageOrange = 3; // Número de alarmas naranjas por página
        $perPageBlack = 3; // Número de alarmas negras por página
    
        $currentPage = $request->input('page', 1);
    
        // Separa las alarmas en tres variables: $redAlarms, $orangeAlarms y $blackAlarms
        $redAlarms = [];
        $orangeAlarms = [];
        $blackAlarms = [];
    
        foreach ($alarmsCollection as $alarm) {
            if ($alarm['color'] === 'red') {
                $redAlarms[] = $alarm;
            } elseif ($alarm['color'] === 'orange') {
                $orangeAlarms[] = $alarm;
            } elseif ($alarm['color'] === 'black') {
                $blackAlarms[] = $alarm;
            }
        }
    
        // Crea una instancia de LengthAwarePaginator para cada conjunto de alarmas
        $redAlarmsPaginator = new LengthAwarePaginator(
            collect($redAlarms)->forPage($currentPage, $perPageRed),
            count($redAlarms),
            $perPageRed,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        $orangeAlarmsPaginator = new LengthAwarePaginator(
            collect($orangeAlarms)->forPage($currentPage, $perPageOrange),
            count($orangeAlarms),
            $perPageOrange,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        $blackAlarmsPaginator = new LengthAwarePaginator(
            collect($blackAlarms)->forPage($currentPage, $perPageBlack),
            count($blackAlarms),
            $perPageBlack,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return [
            $redAlarmsPaginator,
            $orangeAlarmsPaginator,
            $blackAlarmsPaginator,
        ];
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
