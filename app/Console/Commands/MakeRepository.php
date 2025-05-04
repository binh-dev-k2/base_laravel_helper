<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new Repository class';

    public function handle()
    {
        $name = str($this->argument('name'))->studly()->value();

        // Tách path và class
        $pathParts = explode('/', $name);
        $className = array_pop($pathParts);
        $subPath = implode('/', $pathParts);
        $namespacePath = implode('\\', $pathParts);

        // Đường dẫn vật lý
        $directory = app_path('Repositories/' . $subPath);
        $filePath = $directory . '/' . $className . '.php';

        if (File::exists($filePath)) {
            $this->error("❌ Repository '{$className}' already exists.");
            return;
        }

        File::ensureDirectoryExists($directory);

        $namespace = 'App\\Repositories' . ($namespacePath ? '\\' . $namespacePath : '');

        $template = <<<EOT
<?php

namespace {$namespace};

class {$className}
{
    // Implement repository methods here
}
EOT;

        File::put($filePath, $template);

        $this->info("✅ Repository '{$className}' created at app/Repositories/{$subPath}");
    }
}
