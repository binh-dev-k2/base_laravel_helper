<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTrait extends Command
{
    protected $signature = 'make:trait {name}';
    protected $description = 'Create a new Trait class';

    public function handle()
    {
        $name = str($this->argument('name'))->studly()->value();

        // Tách path và class
        $pathParts = explode('/', $name);
        $className = array_pop($pathParts);
        $subPath = implode('/', $pathParts);
        $namespacePath = implode('\\', $pathParts);

        // Đường dẫn vật lý
        $directory = app_path('Traits/' . $subPath);
        $filePath = $directory . '/' . $className . '.php';

        if (File::exists($filePath)) {
            $this->error("❌ Trait '{$className}' already exists.");
            return;
        }

        File::ensureDirectoryExists($directory);

        $namespace = 'App\\Traits' . ($namespacePath ? '\\' . $namespacePath : '');
        $template = <<<EOT
<?php

namespace {$namespace};

trait {$className}
{
    // Implement your methods here
}
EOT;

        File::put($filePath, $template);

        $this->info("✅ Trait '{$className}' created at app/Traits/{$subPath}");
    }
}
