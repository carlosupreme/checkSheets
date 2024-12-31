<?php

namespace Database\Seeders;

use App\Models\CheckSheet;
use App\Models\CheckSheetItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CheckSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $checkSheet = CheckSheet::create([
            'name'           => 'Mangle 02',
            'equipment_area' => 'Lavanderia institucional',
            'equipment_name' => 'Mangle',
            'equipment_tag'  => 'LV-MGL-02',
            'is_published'   => true,
            'created_by'     => 1,
            'updated_by'     => 1,
            'notes'          => 'NOTA: Cuando se revise el funcionamiento del termostato verificar que el cañon encienda todo el quemador.'
        ]);

        $i = 1;
        CheckSheetItem::create([
            'check_sheet_id' => $checkSheet->id,
            'order'          => $i,
            'properties'     => [
                'ITEM DE CHEQUEO'           => 'FUGAS DE GAS L.P.',
                'FRECUENCIA'                => 'AL INICIO DEL TURNO',
                'METODO DE CHEQUEO'         => 'REVISAR FISICAMENTE',
                'CRITERIO DE DETERMINACION' => 'FUGAS DE GAS EN CONEXIONES',
                'QUIEN REALIZA'             => 'OPERARIO',
                'OBSERVACIONES'             => 'NO DEBEN EXISTIR FUGAS EN LAS CONEXIONES'
            ]
        ]);

        $i++;

        CheckSheetItem::create([
            'check_sheet_id' => $checkSheet->id,
            'order'          => $i,
            'properties'     => [
                'ITEM DE CHEQUEO'           => 'LIMPIEZA DE FILTRO',
                'FRECUENCIA'                => 'CADA 8 HRS',
                'METODO DE CHEQUEO'         => 'MANUAL',
                'CRITERIO DE DETERMINACION' => 'ELIMINAR EL EXCESO DE PELUSA',
                'QUIEN REALIZA'             => 'OPERARIO',
                'OBSERVACIONES'             => 'PARA MANTENER EL CORRECTO ENCENDIDO DEL QUEMADOR'
            ]
        ]);

        $i++;

        CheckSheetItem::create([
            'check_sheet_id' => $checkSheet->id,
            'order'          => $i,
            'properties'     => [
                'ITEM DE CHEQUEO'           => 'CINTAS GUIAS',
                'FRECUENCIA'                => '1 VEZ POR TURNO',
                'METODO DE CHEQUEO'         => 'VISUAL',
                'CRITERIO DE DETERMINACION' => 'QUE NO ESTEN ROTAS, DESGASTADAS, FUERA DE SU LUGAR Y ENGRAPADAS',
                'QUIEN REALIZA'             => 'OPERARIO',
                'OBSERVACIONES'             => 'CUANDO ESTE APAGADA Y FRIA'
            ]
        ]);

    }
}
