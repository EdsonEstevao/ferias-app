<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = [];
        //   $users = User::with('roles')
        //                 ->get()
        //                 ->map(function($user) {
        //                     return [
        //                         'id' => $user->id,
        //                         'name' => $user->name,
        //                         'email' => $user->email,
        //                         'roles' => $user->roles->pluck('name'), // Já retorna array
        //                         'created_at' => $user->created_at->toISOString(),
        //                         'initials' => strtoupper(
        //                             collect(explode(' ', $user->name))
        //                                 ->map(fn($part) => substr($part, 0, 1))
        //                                 ->take(2)
        //                                 ->join('')
        //                         )
        //                     ];
        // });

        // $users = User::with('roles')->where('id', '!=', Auth::user()->id)->paginate(10)->withQueryString();

        // $roles = Role::all();

        // $data = [
        //     'users' => $users,
        //     'roles' => $roles
        // ];

        // // dd($data);
        // return view('admin.users.index', $data);

        $query = User::with('roles');

        // Contagem total de usuários
        $totalUsers = User::count();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Contagem após filtros
        $filteredUsers = $query->count();

        // Ordenação
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Paginação
        $users = $query->paginate(10)->withQueryString();
        $displayedUsers = $users->count();

        $roles = Role::all();

         if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'totalUsers' => $totalUsers,
                'filteredUsers' => $filteredUsers,
                'displayedUsers' => $displayedUsers
            ]);
        }

        return view('admin.users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'filteredUsers',
            'displayedUsers'
        ));


    }

    public function create()
    {
         return view('admin.users.create', [
            'users' => User::with('roles')->get(),
            'roles' => Role::all(),
        ]);
        // return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user, Request $request)
    {
        // dd($user, $request->all());
        // return view('admin.users.edit', [
        //     'user' => $user::with('roles')->find($user->id),
        //     'roles' => Role::all(),
        // ]);

         // Prevenir que Admin edite Super Admin
        if (Auth::user()->hasRole('admin') && $user->hasRole('super admin')) {
            abort(403, 'Não permitido editar Super Admin');
        }

        $roles = Role::where('name', '!=', 'super admin')->get(); // Admin não vê Super Admin

        return view('admin.users.edit', compact('user', 'roles'));
    }



    public function update(Request $request, User $user)
    {
          // Validar permissões
        if (Auth::user()->hasRole('admin') && $user->hasRole('super admin')) {
            abort(403, 'Não permitido modificar Super Admin');
        }

        // Se for Admin, não permitir atribuir role Super Admin
        if (Auth::user()->hasRole('admin') && $request->role === 'super admin') {
            abort(403, 'Não permitido atribuir role Super Admin');
        }



        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Usuário atualizado com sucesso!');
    }

    public function updateRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        return back()->with('success', 'Roles atualizadas!');
    }

}
