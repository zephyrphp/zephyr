<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;

final class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        return redirect('/');
    }
}
