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

/** 
 * По хорошему должно быть так
 */ 
class AuthController
{
    public function register(Request $request, CommandHandler $handler)
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

/** 
 * Есть два решения!
 *
 * 1) Импорт через namespace
 * 2) invoke контроллеры 
 * 
 * Рассмотрим первый вариант
 */
 
use App\UseCases\Commands\Auth as CommandsUseCases
use App\UseCases\Queries\Auth as QueriesUseCases
 
class AuthController
{
    public function register(
        Request $request,
        CommandsUseCases\Register\CommandHandler $handler
    )
    {
        return $handler->handle(
            CommandsUseCases\Register\Command::fillAttributes(
                $request->all()
            )
        )
    }
    
    /** 
     * Код получается не очень то читабельным 
     * и не думаю что импорт через неймспейсы хорошее решение
     */
}

/** 
 * Второй же вариант с invoke классами 
 * 
 * Сематически код тоже будет разделяться 
 * по доменам в директории контроллеров
 * 
 * App/Http/Controllers/Api/Auth/
 *          - RegisterController
 *          - LoginController
 * 
 * В роуте регистрируем максимально просто
 * 
 * Route::prefix('auth')->group(function () {
        Route::post('/register', RegisterController::class)    
 * })
 * 
 * Сам же контроллер реализуем следующим образом
 */

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
    
    /** 
     * Таким образом у нас нет проблем с импортами и 
     * использованием разных команд
     */
}
```