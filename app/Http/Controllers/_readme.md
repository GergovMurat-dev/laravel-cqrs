Так как в архитектуре проекта используются следующая структура

```text
UseCases/
    /Commands
        /{Домен}
    /Queries
        /{Домен}
```

Нельзя адекватно реализовать вызов команд и запросов в одном контроллере, так как происходит следующее

```php

use Illuminate\Http\Request
use App\UseCases\Commands\Auth\Register

// По хорошему должно быть так
class AuthController
{
    public function registerRequest(Request $request, CommandHandler $handler)
    {
        return $handler->handle(
            Command::fillAttributes($request->all())
        )
    }
    
    /** 
     * Проблема в том что у нас название команд и хендлеров одинковое, 
     * тем самым мы не можем просто взять и вызвать таким образом 
     */
}

```