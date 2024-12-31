<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

/**
 * Prunable models :
 * - User
 */
Schedule::command('model:prune')->daily();
