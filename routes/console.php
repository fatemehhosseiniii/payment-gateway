<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:expire-transaction-command')->everyMinute();
