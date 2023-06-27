<?php
namespace App\Exports;

use App\Models\{GameInstance, User};
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GameInstanceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    private $exercises;
    private $gameInstance;

    public function __construct($id)
    {
        $this->gameInstance = GameInstance::find($id);
        $this->exercises = $this->gameInstance->exercises;
    }

    public function collection()
    {
        return $this->exercises;
    }
    
    public function headings(): array
    {
        return [
            'Nombre de la instancia',
            'Nombre del usuario',
            'Ronda',
            'Ejercicio',
            'Respuesta del usuario',
            'Respuesta correcta',
            'Puntaje',
            'Vidas',
            'tiempo de comienzo',
            'tiempo de finalizacion'
        ];
    }

    public function map($row): array
    {
        $user = User::find($row->user_id);

        return [
            $this->gameInstance->name ?? null,
            $user->name ?? null,
            $row->round,
            $row->exercise,
            $row->user_response,
            $row->response,
            $row->score,
            $row->lives,
            $row->time_start,
            $row->time_end,
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']],
            ],
        ];
    }
}