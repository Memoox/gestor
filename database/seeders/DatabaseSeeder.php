<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //DepartamentoSeeder::class,
            TipoUsuarioSeeder::class,
            //UserSeeder::class,
            //TipoDocumentoSeeder::class,
            PrioridadSeeder::class,
            //CategoriaSeeder::class,
            EstatusSeeder::class,
            // DocumentoSeeder::class,
            // EvidenciaSeeder::class,
        ]);
    }
}
