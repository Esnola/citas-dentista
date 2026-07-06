<?php

namespace Database\Seeders;

use App\Models\TwilioContentTemplate;
use Illuminate\Database\Seeder;

class TwilioContentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'nombre' => 'Dos Botones Nuevo',
                'content_sid' => 'HX3e116fa6be92c8ef9db84b65c383d5bc',
                'seleccionada' => true,
                'content_variables' => ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
            ],
            [
                'nombre' => 'Dos botones Antiguo',
                'content_sid' => 'HXdea6aee77629b70b2ca3298e0e2ec5f2',
                'seleccionada' => false,
                'content_variables' => ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
            ],
            [
                'nombre' => 'Confirmar Texto con Emoji',
                'content_sid' => 'HX28712cac47e020331237e0dfb9228aaf',
                'seleccionada' => false,
                'content_variables' => ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
            ],
            [
                'nombre' => 'Confirmar Texto',
                'content_sid' => 'HX94dfe8732cc8177e79e8003da08be354',
                'seleccionada' => false,
                'content_variables' => ['1' => '[NOMBRE]', '2' => '[DIA]', '3' => '[HORA]'],
            ],
        ];

        foreach ($templates as $template) {
            TwilioContentTemplate::updateOrCreate(
                ['content_sid' => $template['content_sid']],
                $template
            );
        }
    }
}
