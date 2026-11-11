<?php

use Illuminate\Support\Facades\Schedule;

// E-mail reminders for tasks whose `remind_at` has passed.
Schedule::command('tasks:send-reminders')->everyMinute()->withoutOverlapping();

// Prune expired Sanctum tokens and used/expired auth codes.
Schedule::command('sanctum:prune-expired --hours=24')->daily();
Schedule::command('auth:prune-codes')->hourly();
