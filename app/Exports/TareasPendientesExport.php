<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Tarea;

class TareasPendientesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tarea::with('user:id,nombre,email')
            ->where('estado', 'pendiente')
            ->select('id', 'titulo', 'descripcion', 'fecha_vencimiento', 'user_id', 'created_at')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Título',
            'Descripción',
            'Fecha de Vencimiento',
            'Usuario Asignado',
            'Fecha de Creación'
        ];
    }

    /**
     * @param mixed $tarea
     * @return array
     */
    public function map($tarea): array
    {
        return [
            $tarea->titulo,
            $tarea->descripcion ?? 'Sin descripción',
            $tarea->fecha_vencimiento->format('d/m/Y'),
            $tarea->user ? $tarea->user->nombre : 'Sin asignar',
            $tarea->created_at->format('d/m/Y H:i')
        ];
    }
}
