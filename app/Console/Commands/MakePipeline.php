<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePipeline extends Command
{
    protected $signature = 'make:pipeline {name}';
    protected $description = 'Create a new Pipeline step class';

    public function handle()
{
    $name = str($this->argument('name'))->studly()->value();

    // Tách path và class
    $pathParts = explode('/', $name);
    $className = array_pop($pathParts);
    $subPath = implode('/', $pathParts);
    $namespacePath = implode('\\', $pathParts);

    // Đường dẫn vật lý
    $directory = app_path('Pipelines/' . $subPath);
    $filePath = $directory . '/' . $className . '.php';

    if (File::exists($filePath)) {
        $this->error("❌ Pipeline '{$className}' already exists.");
        return;
    }

    File::ensureDirectoryExists($directory);

    $namespace = 'App\\Pipelines' . ($namespacePath ? '\\' . $namespacePath : '');

    $template = <<<EOT
<?php

namespace {$namespace};

use Closure;

class {$className}
{
    public function handle(\$payload, Closure \$next)
    {
        // TODO: implement logic here

        return \$next(\$payload);
    }
}
EOT;

    File::put($filePath, $template);

    $this->info("✅ Pipeline '{$className}' created at app/Pipelines/{$subPath}");
}

}
