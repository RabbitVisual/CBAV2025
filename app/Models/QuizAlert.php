<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAlert extends Model
{
    use HasFactory;

    protected $table = 'cbav_quiz_alerts';

    protected $fillable = [
        'quiz_session_id',
        'user_id',
        'alert_type',
        'title',
        'message',
        'quiz_data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'quiz_data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Relacionamentos
    public function quizSession(): BelongsTo
    {
        return $this->belongsTo(\App\Models\EbdQuizSessao::class, 'quiz_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Métodos de instância
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Métodos estáticos para criação de alertas
    public static function createNewRecord($userId, $quizSessionId, $score, $category = null): self
    {
        $quizData = [
            'score' => $score,
            'category' => $category,
            'achievement_type' => 'new_record',
            'timestamp' => now()->toISOString()
        ];

        return static::create([
            'quiz_session_id' => $quizSessionId,
            'user_id' => $userId,
            'alert_type' => 'new_record',
            'title' => 'Novo Recorde Pessoal! 🏆',
            'message' => "Parabéns! Você estabeleceu um novo recorde com {$score} pontos" . 
                        ($category ? " na categoria {$category}" : '') . '!',
            'quiz_data' => $quizData
        ]);
    }

    public static function createAchievement($userId, $achievementType, $title, $message, $quizData = []): self
    {
        return static::create([
            'user_id' => $userId,
            'alert_type' => 'achievement',
            'title' => $title,
            'message' => $message,
            'quiz_data' => array_merge($quizData, [
                'achievement_type' => $achievementType,
                'timestamp' => now()->toISOString()
            ])
        ]);
    }

    public static function createReminder($userId, $title, $message, $reminderData = []): self
    {
        return static::create([
            'user_id' => $userId,
            'alert_type' => 'reminder',
            'title' => $title,
            'message' => $message,
            'quiz_data' => array_merge($reminderData, [
                'reminder_type' => 'quiz_participation',
                'timestamp' => now()->toISOString()
            ])
        ]);
    }

    public static function createResult($userId, $quizSessionId, $score, $totalQuestions, $percentage): self
    {
        $quizData = [
            'score' => $score,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'result_type' => 'completion',
            'timestamp' => now()->toISOString()
        ];

        $encouragement = static::getEncouragementMessage($percentage);

        return static::create([
            'quiz_session_id' => $quizSessionId,
            'user_id' => $userId,
            'alert_type' => 'result',
            'title' => 'Quiz Concluído! 📚',
            'message' => "Você acertou {$score} de {$totalQuestions} questões ({$percentage}%). {$encouragement}",
            'quiz_data' => $quizData
        ]);
    }

    public static function createStreakAlert($userId, $streakCount, $streakType = 'daily'): self
    {
        $title = match($streakType) {
            'daily' => 'Sequência Diária! 🔥',
            'weekly' => 'Sequência Semanal! ⭐',
            'perfect' => 'Sequência Perfeita! 💎',
            default => 'Sequência Mantida! 🎯'
        };

        $message = "Incrível! Você mantém uma sequência de {$streakCount} " . 
                  match($streakType) {
                      'daily' => 'dias consecutivos',
                      'weekly' => 'semanas consecutivas',
                      'perfect' => 'quizzes perfeitos',
                      default => 'participações'
                  } . ' participando dos quizzes!';

        return static::create([
            'user_id' => $userId,
            'alert_type' => 'achievement',
            'title' => $title,
            'message' => $message,
            'quiz_data' => [
                'streak_count' => $streakCount,
                'streak_type' => $streakType,
                'achievement_type' => 'streak',
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    public static function createLevelUpAlert($userId, $newLevel, $previousLevel): self
    {
        return static::create([
            'user_id' => $userId,
            'alert_type' => 'achievement',
            'title' => 'Nível Aumentado! 🆙',
            'message' => "Parabéns! Você avançou do nível {$previousLevel} para o nível {$newLevel}. Continue estudando a Palavra!",
            'quiz_data' => [
                'new_level' => $newLevel,
                'previous_level' => $previousLevel,
                'achievement_type' => 'level_up',
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    // Métodos auxiliares
    private static function getEncouragementMessage($percentage): string
    {
        return match(true) {
            $percentage >= 90 => 'Excelente conhecimento da Palavra! 🌟',
            $percentage >= 80 => 'Muito bom! Continue estudando! 📖',
            $percentage >= 70 => 'Bom trabalho! A prática leva à perfeição! 💪',
            $percentage >= 60 => 'Continue se esforçando! Você está no caminho certo! 🙏',
            default => 'Não desista! Cada tentativa é um aprendizado! ❤️'
        };
    }

    // Accessors
    public function getTypeIconAttribute(): string
    {
        return match($this->alert_type) {
            'new_record' => 'fas fa-trophy',
            'achievement' => 'fas fa-medal',
            'reminder' => 'fas fa-bell',
            'result' => 'fas fa-chart-line',
            default => 'fas fa-info-circle'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->alert_type) {
            'new_record' => 'gold',
            'achievement' => 'purple',
            'reminder' => 'blue',
            'result' => 'green',
            default => 'gray'
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->alert_type) {
            'new_record' => 'bg-yellow-100 text-yellow-800',
            'achievement' => 'bg-purple-100 text-purple-800',
            'reminder' => 'bg-blue-100 text-blue-800',
            'result' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedQuizDataAttribute(): array
    {
        return $this->quiz_data ?? [];
    }

    public function getIsImportantAttribute(): bool
    {
        return in_array($this->alert_type, ['new_record', 'achievement']);
    }
}