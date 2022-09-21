<?php
namespace GLC\Batch\Console\Providers;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use GLC\Batch\Console\Commands\DownService;
use ReflectionClass;
use ReflectionException;
use GLC\Platform\Console\Contracts\Scheduler as SchedulerContract;
use GLC\Batch\Console\Scheduler;

/**
 * Artisanに関連した設定を行うプロバイダークラス。
 *
 * @package Wanriku\Api\Console\Providers
 * @author  TinhNC <tinhhang22@gmail.com>
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * アプリケーションから提供されるArtisanコマンドの定義。
     * @var array
     */
    protected array $commands = [
        \GLC\Batch\Console\Commands\DownService::class,
    ];

    /**
     * サービスの登録処理を行う。
     *
     * @return void
     */
    public function register()
    {
        $this->loadCommands();

        //既存downコマンドをregister
        $this->app->extend('command.down', function () {
            return new DownService();
        });

        $this->app->bind(SchedulerContract::class, function () {
            return new Scheduler();
        });
    }

    /**
     * アプリケーションで使用するArtisanコマンドを読み込んで使用可能にする。
     *
     * @return void
     */
    private function loadCommands()
    {
        foreach ($this->commands as $command) {
            try {
                if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract()) {
                    Artisan::starting(function ($artisan) use ($command) {
                        /** @var Artisan $artisan */
                        $artisan->resolve($command);
                    });
                }
            } catch (ReflectionException $e) {
                Log::channel('error')->error($e->getMessage());
            }
        }
    }
}