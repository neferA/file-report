<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            // tabla roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',
            // tabla usuarios
            'crud-usuario',
            //tabla blogs esto es un crud de prueba
            'ver_tickets',
            'crear_tickets',
            'editar_tickets',
            'borrar_tickets',
        ];
        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}
