<?php

namespace App\UseCases\Commands\Auth\Register;


use App\Actions\CreateUserAction;
use Thumbrise\Toolkit\Opresult\OperationResult;
use Thumbrise\Toolkit\Opresult\Validator;

class CommandHandler
{
    public function __construct(
        private readonly CreateUserAction $createUser
    )
    {
    }

    public function handle(Command $command): OperationResult
    {
        $v = Validator::validate(
            $command->toArray(),
            [
                'email' => 'required|email',
                'name' => 'required|string',
                'password' => 'required|string' // Так не стоит делать, сделано чтобы не заморачиваться
            ]
        );

        if ($v->isError()) {
            return $v;
        }

        $createUserOperationResult = $this->createUser->do(
            $command->name,
            $command->email,
            $command->password
        );
        if ($createUserOperationResult->isError()) {
            return $createUserOperationResult;
        }


        return OperationResult::success();
    }
}