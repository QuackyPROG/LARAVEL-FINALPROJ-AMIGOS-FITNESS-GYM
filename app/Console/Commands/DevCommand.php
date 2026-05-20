<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DevCommand extends Command
{
    protected $signature = 'dev
                            {--host=127.0.0.1 : The HTTP server host}
                            {--port=8000 : The HTTP server port}
                            {--no-vite : Skip starting the Vite dev server}';

    protected $description = 'Start HTTP server, Reverb WebSocket server, and Vite in one command';

    /** @var Process[] */
    private array $processes = [];

    public function handle(): int
    {
        $php = PHP_BINARY;

        $this->processes = [
            'serve'  => new Process([$php, 'artisan', 'serve', '--host='.$this->option('host'), '--port='.$this->option('port')]),
            'reverb' => new Process([$php, 'artisan', 'reverb:start']),
        ];

        if (! $this->option('no-vite')) {
            $this->processes['vite'] = new Process(['npm', 'run', 'dev']);
        }

        $this->line('');
        $this->line('  <fg=yellow;options=bold>AmigosFitnessGym — Dev Server</>');
        $this->line('');

        foreach ($this->processes as $name => $process) {
            $process->setTimeout(null);
            $process->start(function (string $type, string $output) use ($name): void {
                foreach (explode("\n", rtrim($output)) as $line) {
                    if ($line === '') {
                        continue;
                    }
                    $tag = match ($name) {
                        'serve'  => 'fg=blue',
                        'reverb' => 'fg=yellow',
                        'vite'   => 'fg=green',
                        default  => 'fg=white',
                    };
                    $this->line("  <{$tag}>[{$name}]</> {$line}");
                }
            });
            $this->line("  <options=bold>Started:</> {$name}");
        }

        $this->line('');
        $this->line('  Press <options=bold>Ctrl+C</> to stop all servers.');
        $this->line('');

        // Register SIGINT handler (Unix) to stop children cleanly
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function (): void {
                $this->stopAll();
                exit(0);
            });
        }

        while (true) {
            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }

            foreach ($this->processes as $name => $process) {
                if (! $process->isRunning()) {
                    $this->newLine();
                    $this->error("  [{$name}] exited unexpectedly (code {$process->getExitCode()}).");
                    $this->stopAll();

                    return self::FAILURE;
                }
            }

            usleep(250_000);
        }
    }

    private function stopAll(): void
    {
        foreach (array_reverse($this->processes) as $name => $process) {
            if ($process->isRunning()) {
                $process->stop(3);
                $this->line("  Stopped {$name}.");
            }
        }
    }
}
