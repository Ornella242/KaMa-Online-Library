<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Liste des rôles administratifs.
     */
    public function index()
    {
        abort_unless(
            Auth::user()->hasAdminPermission('roles.view'),
            403
        );

        $roles = Role::query()
            ->where('name', '!=', 'reader')
            ->where('name', '!=', 'writer')
            ->withCount(['adminUsers', 'permissions'])
            ->with('permissions')
            ->orderByRaw("CASE WHEN name = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        $permissions = Permission::query()
            ->orderBy('name')
            ->get();

        $permissionGroups = $permissions->groupBy(
            fn ($permission) => Str::beforeLast($permission->name, '.')
        );

        return view('admin.roles.index', compact(
            'roles',
            'permissions',
            'permissionGroups'
        ));
    }


    /**
     * Création d'un rôle.
     *
     * Le formulaire est affiché dans l'overlay de index.blade.php.
     */
    public function create()
    {
        abort_unless(
            Auth::user()->hasAdminPermission('roles.create'),
            403
        );

        return redirect()->route('admin.roles.index', [
            'create' => 1
        ]);
    }


    /**
     * Enregistre un nouveau rôle.
     */
    public function store(Request $request)
    {
        abort_unless(
            Auth::user()->hasAdminPermission('roles.create'),
            403
        );

        $data = $request->validate([

            'label' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],

        ], [

            'label.required' => 'Le nom du rôle est obligatoire.',
            'label.max' => 'Le nom du rôle ne peut pas dépasser 100 caractères.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
            'permissions.*.exists' => 'Une permission sélectionnée est invalide.',
        ]);


        /* Génération du nom technique */

        $name = Str::slug($data['label'], '_');


        /* Éviter les noms réservés */

        if (in_array($name, [
            'reader',
            'writer',
            'admin',
        ])) {

            return back()
                ->withErrors([
                    'label' => 'Ce nom de rôle est réservé au système.',
                ])
                ->withInput();

        }


        /* Éviter les doublons */

        $originalName = $name;

        $counter = 2;

        while (
            Role::query()
                ->where('name', $name)
                ->exists()
        ) {

            $name = $originalName . '_' . $counter;

            $counter++;
        }


        /* Création */

        DB::transaction(function () use ($data, $name) {

            $role = Role::create([
                'name' => $name,
                'label' => $data['label'],
                'description' => $data['description'] ?? null,
            ]);

            $role->permissions()->sync(
                $data['permissions'] ?? []
            );

        });


        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Le rôle "' . $data['label'] . '" a été créé avec succès.'
            );
    }


    /**
     * Formulaire d'édition.
     */
    public function edit(Role $role)
    {
       
    }


    /**
     * Mise à jour d'un rôle.
     */
    public function update(Request $request, Role $role)
    {
        abort_unless(
            Auth::user()->hasAdminPermission('roles.edit'),
            403
        );

        abort_if(
            in_array($role->name, [
                'reader',
                'writer',
                'admin',
            ]),
            403
        );

        $data = $request->validate([

            'label' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],

        ], [

            'label.required' => 'Le nom du rôle est obligatoire.',

            'label.max' => 'Le nom du rôle ne peut pas dépasser 100 caractères.',

            'description.max' => 'La description ne peut pas dépasser 255 caractères.',

            'permissions.*.exists' => 'Une permission sélectionnée est invalide.',
        ]);


        DB::transaction(function () use ($data, $role) {

            $role->update([
                'label' => $data['label'],
                'description' => $data['description'] ?? null,
            ]);

            $role->permissions()->sync(
                $data['permissions'] ?? []
            );

        });


        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Le rôle "' . $data['label'] . '" a été mis à jour avec succès.'
            );
    }

    /**
     * Suppression d'un rôle personnalisé.
     */
    public function destroy(Role $role)
    {
        abort_unless(
            Auth::user()->hasAdminPermission('roles.delete'),
            403
        );

        abort_if(
            in_array($role->name, [
                'reader',
                'writer',
                'admin',
            ]),
            403
        );

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Le rôle a été supprimé avec succès.');
    }
}