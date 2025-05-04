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
        $name = $this->argument('name');
        $className = str($name)->studly()->value();
        $path = app_path("Pipelines/{$className}.php");

        if (File::exists($path)) {
            $this->error('Pipeline class already exists!');
            return;
        }

        $stub = <<<EOT
<?php

namespace App\Pipelines;

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

        File::ensureDirectoryExists(app_path('Pipelines'));
        File::put($path, $stub);

        $this->info("Pipeline class [{$className}] created successfully at app/Pipelines.");
    }
}
