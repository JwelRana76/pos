<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the service class name from the input
        $name = $this->argument('name');

        // Determine the full file path
        $path = app_path("Service/{$name}.php");

        // Check if the service class already exists
        if (File::exists($path)) {
            $this->error("Service {$name} already exists!");
            return 1;
        }

        // Ensure the Services directory exists
        if (!File::isDirectory(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        // Create the service class content
        $serviceTemplate = $this->getServiceTemplate($name);

        // Write the content to the new service class file
        File::put($path, $serviceTemplate);

        // Output a success message
        $this->info("Service {$name} created successfully.");

        return 0;
    }

    /**
     * Get the template for the service class.
     *
     * @param string $name
     * @return string
     */
    protected function getServiceTemplate($name)
    {
        return <<<EOD
<?php

namespace App\Services;

class {$name}
{
    // Define your service methods here
}
EOD;
    }
}
