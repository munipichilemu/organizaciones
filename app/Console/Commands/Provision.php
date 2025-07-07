<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

class Provision extends Command
{
    protected $signature = 'provision:generate
                        {--frankenphp : Generate web server configuration (FrankenPHP + Caddy)}
                        {--systemd : Generate system service configuration file}
                        {--force : Overwrite existing files without confirmation}
                        {--path= : Custom output directory for generated files}';

    protected $description = 'Generate deployment configuration files for Laravel applications';

    public function handle(): int
    {
        intro('Laravel Application Provisioning Assistant');
        info('This tool helps you generate configuration files for deploying your Laravel application.');

        $this->displayMenu();

        return self::SUCCESS;
    }

    public function getProvisioningPath(): string|int
    {
        $path = $this->option('path') ?: base_path('provisioning');

        try {
            File::ensureDirectoryExists($path);

            return $path;
        } catch (Exception $e) {
            error("Cannot access or create directory: {$path}");
            error("Error: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function displayMenu(): void
    {
        match ($this->getMenuOption()) {
            'frankenphp' => $this->generateFrankenPHPFile(),
            'systemd' => $this->generateSystemdFile(),
            'exit' => null,
        };
    }

    private function getMenuOption(): int|string
    {
        if ($this->option('frankenphp')) {
            return 'frankenphp';
        }

        if ($this->option('systemd')) {
            return 'systemd';
        }

        return select(
            label: 'Which file do you want to generate?',
            options: [
                'frankenphp' => '🐘 Web Server Configuration (FrankenPHP + Caddy)',
                'systemd' => '⚙️ System Service Configuration (systemd)',
                'exit' => '❌ Exit without generating files',
            ],
            hint: 'Choose the type of configuration file you need for your deployment'
        );
    }

    private function generateFrankenPHPFile(): int
    {
        info('📝 Let\'s configure your web server settings...');

        $data = form()
            ->text(
                label: 'What domain will your application use?',
                placeholder: 'example.com',
                default: str_replace(
                    ['http://', 'https://'],
                    '',
                    config('app.url', 'localhost')
                ),
                required: true,
                hint: 'Enter your domain without http:// or https://',
                name: 'domain'
            )
            ->text(
                label: 'How much memory should PHP use?',
                placeholder: '256M, 512M, 1G',
                default: '256M',
                required: true,
                hint: 'Recommended: 256M for small apps, 512M+ for larger applications',
                name: 'memory_limit'
            )
            ->text(
                label: 'What timezone should your server use?',
                default: config('app.timezone', 'UTC'),
                required: true,
                validate: function (string $value): ?string {
                    return in_array($value, timezone_identifiers_list())
                        ? null
                        : 'Please enter a valid timezone identifier.';
                },
                hint: 'Use timezone format like: America/New_York, Europe/Madrid, UTC',
                name: 'timezone'
            )
            ->submit();

        $stub_path = base_path('stubs/caddyfile.stub');

        try {
            $content = File::get($stub_path);
        } catch (FileNotFoundException $e) {
            error("Stub file not found: {$stub_path}");

            return self::FAILURE;
        }

        foreach ($data as $key => $value) {
            $content = str_replace(
                search: "{{ {$key} }}",
                replace: $value,
                subject: $content
            );
        }

        $output_path = "{$this->getProvisioningPath()}/Caddyfile";

        if (File::exists($output_path) && ! $this->option('force')) {
            if (! confirm('Do you want to replace the existing Caddyfile?')) {
                warning('⏭️  Skipped Caddyfile generation - existing file preserved.');

                return self::SUCCESS;
            }
        }

        File::put($output_path, $content);
        outro('Web server configuration created successfully!');
        info('📁 Your Caddyfile is ready at:');
        info("$output_path");
        note('💡 Next step: Copy this file to the root of your project or to /etc/caddy/');

        return self::SUCCESS;
    }

    private function generateSystemdFile(): int
    {
        info('⚙️ Let\'s configure your system service settings...');

        $data = form()
            ->text(
                label: 'What is the name of your application?',
                placeholder: 'My Laravel Application',
                default: config('app.name', 'Laravel Application'),
                required: true,
                hint: 'Enter a descriptive name for your application',
                name: 'appName',
                transform: fn (string $value) => trim($value)
            )
            ->text(
                label: 'Which user should run the service?',
                default: 'root',
                required: true,
                hint: 'Recommended: www-data for web applications',
                name: 'user'
            )
            ->text(
                label: 'Which group should run the service?',
                default: 'root',
                required: true,
                hint: 'Usually the same as the user',
                name: 'group'
            )
            ->text(
                label: 'What is the full path to your application?',
                default: base_path(),
                required: true,
                hint: 'The absolute path where your Laravel app is located',
                name: 'appPath'
            )
            ->submit();

        $data['serviceName'] = Str::slug($data['appName']);
        $stub_path = base_path('stubs/systemd-service.stub');

        try {
            $content = File::get($stub_path);
        } catch (FileNotFoundException $e) {
            error("Stub file not found: {$stub_path}");

            return self::FAILURE;
        }

        foreach ($data as $key => $value) {
            $content = str_replace(
                search: "{{ {$key} }}",
                replace: $value,
                subject: $content
            );
        }

        $output_path = "{$this->getProvisioningPath()}/{$data['serviceName']}.service";

        if (File::exists($output_path) && ! $this->option('force')) {
            if (! confirm("Do you want to replace the existing {$data['serviceName']}.service file?")) {
                warning('⏭️  Skipped systemd service generation - existing file preserved.');

                return self::SUCCESS;
            }
        }

        File::put($output_path, $content);
        outro('System service configuration created successfully!');
        info('📁 Your systemd service file is ready at:');
        info("$output_path");
        note("💡 Next step: Copy this file to /etc/systemd/system/ and run 'sudo systemctl daemon-reload'");

        return self::SUCCESS;
    }
}
