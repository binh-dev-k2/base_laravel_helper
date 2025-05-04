<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new Service class';

    public function handle()
    {
        $name = str($this->argument('name'))->studly()->value();

        $pathParts = explode('/', $name);
        $className = array_pop($pathParts);
        $subPath = implode('/', $pathParts);
        $namespacePath = implode('\\', $pathParts);

        $directory = app_path('Services/' . $subPath);
        $filePath = $directory . '/' . $className . '.php';

        if (File::exists($filePath)) {
            $this->error("❌ Service '{$className}' already exists.");
            return;
        }

        File::ensureDirectoryExists($directory);

        $namespace = 'App\\Services' . ($namespacePath ? '\\' . $namespacePath : '');

        $template = <<<EOT
<?php

namespace {$namespace};

class {$className}
{
    // Implement your service methods here
}
EOT;

        File::put($filePath, $template);

        $this->info("✅ Service '{$className}' created at app/Services/{$subPath}");
    }
}
