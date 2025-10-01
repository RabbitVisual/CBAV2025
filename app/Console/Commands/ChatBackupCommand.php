<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Storage;

class ChatBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:backup {--room= : ID da sala específica} {--daily : Backup diário}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar backup das conversas do chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roomId = $this->option('room');
        $isDaily = $this->option('daily');
        
        if ($roomId) {
            $this->backupRoom($roomId);
        } else {
            $this->backupAllRooms($isDaily);
        }
        
        $this->info('Backup do chat concluído com sucesso!');
    }
    
    private function backupRoom($roomId)
    {
        $room = ChatRoom::with(['messages.user', 'participants.user'])->find($roomId);
        
        if (!$room) {
            $this->error("Sala {$roomId} não encontrada!");
            return;
        }
        
        $this->createBackup($room);
        $this->info("Backup da sala '{$room->nome}' criado com sucesso!");
    }
    
    private function backupAllRooms($isDaily)
    {
        $rooms = ChatRoom::with(['messages.user', 'participants.user'])->get();
        
        $this->info("Iniciando backup de {$rooms->count()} salas...");
        
        $bar = $this->output->createProgressBar($rooms->count());
        $bar->start();
        
        foreach ($rooms as $room) {
            $this->createBackup($room, $isDaily);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
    }
    
    private function createBackup($room, $isDaily = false)
    {
        $backupData = [
            'room' => $room->toArray(),
            'messages' => $room->messages->toArray(),
            'participants' => $room->participants->toArray(),
            'backup_date' => now()->toISOString(),
            'backup_type' => $isDaily ? 'daily' : 'manual'
        ];
        
        $suffix = $isDaily ? 'daily' : 'manual';
        $filename = "chat_backup_{$room->id}_{$suffix}_" . now()->format('Y-m-d_H-i-s') . ".json";
        $path = "backups/chat/{$filename}";
        
        Storage::put($path, json_encode($backupData, JSON_PRETTY_PRINT));
    }
} 