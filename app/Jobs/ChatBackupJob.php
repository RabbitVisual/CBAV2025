<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ChatBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutos
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $rooms = ChatRoom::with(['messages.user', 'participants.user'])->get();
            
            foreach ($rooms as $room) {
                $this->createBackup($room);
            }
            
            Log::info('Backup automático do chat concluído com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro no backup automático do chat: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function createBackup($room)
    {
        $backupData = [
            'room' => $room->toArray(),
            'messages' => $room->messages->toArray(),
            'participants' => $room->participants->toArray(),
            'backup_date' => now()->toISOString(),
            'backup_type' => 'automatic'
        ];
        
        $filename = "chat_backup_{$room->id}_auto_" . now()->format('Y-m-d_H-i-s') . ".json";
        $path = "backups/chat/{$filename}";
        
        Storage::put($path, json_encode($backupData, JSON_PRETTY_PRINT));
    }
} 