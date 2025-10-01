<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ChatMessageSent;
use App\Listeners\SendChatNotification;
use App\Events\CouncilMeetingScheduled;
use App\Events\CouncilMeetingStarted;
use App\Events\CouncilMeetingFinished;
use App\Events\CouncilVotingStarted;
use App\Events\CouncilVotingFinished;
use App\Events\CouncilAgendaItemAdded;
use App\Events\CouncilQuorumAlert;
use App\Listeners\CouncilNotificationListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ChatMessageSent::class => [
            SendChatNotification::class,
        ],
        CouncilMeetingScheduled::class => [
            CouncilNotificationListener::class . '@handleMeetingScheduled',
        ],
        CouncilMeetingStarted::class => [
            CouncilNotificationListener::class . '@handleMeetingStarted',
        ],
        CouncilMeetingFinished::class => [
            CouncilNotificationListener::class . '@handleMeetingFinished',
        ],
        CouncilVotingStarted::class => [
            CouncilNotificationListener::class . '@handleVotingStarted',
        ],
        CouncilVotingFinished::class => [
            CouncilNotificationListener::class . '@handleVotingFinished',
        ],
        CouncilAgendaItemAdded::class => [
            CouncilNotificationListener::class . '@handleAgendaItemAdded',
        ],
        CouncilQuorumAlert::class => [
            CouncilNotificationListener::class . '@handleQuorumAlert',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 