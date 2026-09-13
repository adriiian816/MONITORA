<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('wa:send-reminders')->dailyAt('08:00');