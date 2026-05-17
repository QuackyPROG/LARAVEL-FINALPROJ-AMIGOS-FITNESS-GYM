<?php

namespace App\Providers;

use App\Events\PaymentSucceeded;
use App\Listeners\HandlePaymentSucceeded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(PaymentSucceeded::class, HandlePaymentSucceeded::class);

        $this->ensureServeCommandHasBuiltAssets();
    }

    private function ensureServeCommandHasBuiltAssets(): void
    {
        if (! app()->runningInConsole()) {
            return;
        }

        $argv = $_SERVER['argv'] ?? [];
        if (($argv[1] ?? null) !== 'serve') {
            return;
        }

        // If Vite dev server is active, Laravel will use /public/hot and skip manifest assets.
        if (is_file(public_path('hot'))) {
            return;
        }

        if (! $this->viteAssetsNeedBuild()) {
            return;
        }

        $command = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? 'npm.cmd run build'
            : 'npm run build';

        $result = Process::path(base_path())
            ->timeout(0)
            ->run($command);

        if ($result->failed()) {
            throw new RuntimeException(
                "Unable to build frontend assets automatically. Run `npm install && npm run build`, then retry `php artisan serve`.\n"
                .$result->errorOutput()
            );
        }
    }

    private function viteAssetsNeedBuild(): bool
    {
        $manifestPath = public_path('build/manifest.json');
        if (! is_file($manifestPath)) {
            return true;
        }

        $manifestMtime = filemtime($manifestPath) ?: 0;
        $sources = [
            resource_path('css/app.css'),
            resource_path('js/app.js'),
            base_path('vite.config.js'),
            base_path('package.json'),
        ];

        foreach ($sources as $source) {
            if (! is_file($source)) {
                continue;
            }

            if ((filemtime($source) ?: 0) > $manifestMtime) {
                return true;
            }
        }

        return false;
    }
}
