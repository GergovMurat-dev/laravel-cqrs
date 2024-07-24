<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Commands\Auth\Register\Command;
use App\UseCases\Commands\Auth\Register\CommandHandler;
use Illuminate\Http\Request;
use Thumbrise\Toolkit\Opresult\OperationResult;

class RegisterController extends Controller
{
    public function __invoke(
        Request $request,
        CommandHandler $handler
    ): OperationResult
    {
        return $handler->handle(
            Command::fillAttributes(
                $request->all()
            )
        );
    }
}