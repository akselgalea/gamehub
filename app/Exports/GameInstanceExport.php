<?php
namespace App\Exports;

use App\Models\{GameInstance, User};
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GameInstanceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{

    private $game_instance_exercises;

    public function __construct($game_instance_exercises)
    {
        $this->game_instance_exercises = $game_instance_exercises;
    }

    public function collection()
    {
        return $this->game_instance_exercises;
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
        if($row->game_instance_id){
            $row->game_instance_id = GameInstance::find($row->game_instance_id);
        };

        if($row->user_id){
            $row->user_id = User::find($row->user_id);
        };
        
        return [
            $row->game_instance_id->name ?? null,
            $row->user_id->name ?? null,
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
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']],
            ],
        ];
    }
}