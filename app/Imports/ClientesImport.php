<?php

namespace App\Imports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ClientesImport implements ToModel, WithHeadingRow, WithMultipleSheets, SkipsEmptyRows
{
    public function sheets(): array
    {
        // Esto procesa todas las pestañas del Excel
        return [
            0 => $this, 1 => $this, 2 => $this, 3 => $this, 4 => $this, 5 => $this
        ];
    }

    public function model(array $row)
    {
        // 1. Limpiamos el RUC por si viene con espacios
        $ruc = isset($row['ruc']) ? trim($row['ruc']) : null;

        // 2. Si no hay RUC o Razón Social, o si la fila es el encabezado repetido, ignoramos
        if (!$ruc || !isset($row['id_del_cliente']) || $ruc == 'RUC') {
            return null;
        }

        // 3. LA MAGIA: updateOrCreate evita el error de duplicados
        return Cliente::updateOrCreate(
            ['ruc' => $ruc], // Condición de búsqueda (el RUC es único)
            [
                'razon_social' => $row['id_del_cliente'], // Columna B
                'lugar'        => $row['lugar'] ?? null,
                'direccion'    => $row['direccion'] ?? null,
                'telefono'     => $row['telefono'] ?? null,
                'contacto'     => $row['nombre_de_contacto'] ?? null,
                'email'        => $row['direccion_de_correo_electronico'] ?? null,
            ]
        );
    }
}




