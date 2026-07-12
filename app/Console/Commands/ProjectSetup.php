<?php

namespace App\Console\Commands;

use App\Library\Laravel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProjectSetup extends Command
{
    /*
     * 1. start, 2. database, 3 migration
     * */
    protected $signature = 'project-setup';

    protected $description = 'Useful to Setup the Project';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        /**
         * Project Setup Should Run only on Local
         * (Some Dependencies like fake is not supporting on production )
         */

        if (!file_exists(base_path('.env'))) {

            $this->warn('Env File is Not Available. Do not worry. Ingenious is creating it for You. Start Again Project Setup :)');

            exec("cp .env.example .env");

            \Artisan::call('key:generate');

            $this->info('========> Project Key Generated <========');

            exit();
        }

        if (env('APP_ENV') !== 'local') {

            $this->error('Setup can not be run on Live or Production Environment, Set to Local');

            exit();
        }

        $commands = ['project', 'database', 'install'];

        $headers = ['#', 'Particular', 'Description', 'Command'];

        $this->table($headers, [
            [1, 'Project Details', 'Enter Details of The Project', 'project'],
            [2, 'Database Connection Details', 'Details database Connection', 'database'],
            [3, 'Start Installation', 'To Install the Project', 'install'],
        ]);

        $step_name = $this->anticipate('Enter Your Command...', $commands);

        if (!in_array($step_name, $commands))
            $this->error('Invalid Command for Deployment.... Try Again...');

        /* Setup Project Config */
        if ($step_name == 'project')
        {
            $company_name = $this->ask('Enter Company Name', 'Network Pvt. Ltd.');
            Laravel::env('COMPANY_NAME', $company_name, 'string');

            $company_brand_name = $this->ask('Enter Company Brand Name', 'Ingenious');
            Laravel::env('BRAND_NAME', $company_brand_name, 'string');
            Laravel::env('APP_NAME', $company_brand_name, 'string');

            $company_url = $this->ask('Enter Company Website url with http & forward `/`', 'http://mlm-app.local/');
            Laravel::env('APP_URL', $company_url);

            $this->warn('Run next Command ====> database <====');
        }

        /* Setup Database */
        if ($step_name == 'database')
        {
            $brand = Str::slug(config('project.brand'), '-');

            $database_name = $this->ask('Enter Database Name', 'mlm_' . $brand);
            Laravel::env('DB_DATABASE', $database_name);

            $database_username = $this->ask('Enter Database User Name', 'root');
            Laravel::env('DB_USERNAME', $database_username);

            $database_password = $this->ask('Enter Database User Password', 'root');
            Laravel::env('DB_PASSWORD', $database_password);
        }

        /* Setup Database */
        if ($step_name == 'install')
        {
            $development = $this->ask('Project in Development Mode? (y/n)', 'y');

            $brand = Str::slug(config('project.brand'), '-');

            /* Directory Setup */
            if ($development == 'y')
                $this->error('All AWS S3 Path will be set with "Development Folder"');
            else
                $this->info('All AWS S3 Path will be set with "Live Mode" ');

            $development_folder = $development == 'y' ? 'development/' : null;

            /* Env Variables */
            Laravel::env('PAYMENT_RECEIPT_PATH', '/web-projects/' . $development_folder .  $brand . '/payment-receipt/');
            Laravel::env('PAYMENT_RECEIPT_IMAGE_URL', env('S3_URL') . $development_folder . $brand . '/payment-receipt/');

            Laravel::env('DOCUMENT_IMAGE_PATH', '/web-projects/' . $development_folder .  $brand . '/documents/');
            Laravel::env('DOCUMENT_IMAGE_URL', env('S3_URL') . $development_folder . $brand . '/documents/');

            Laravel::env('SUPPORT_IMAGE_PATH', '/web-projects/' . $development_folder .  $brand . '/support/');
            Laravel::env('SUPPORT_IMAGE_URL', env('S3_URL') . $development_folder . $brand . '/support/');

            Laravel::env('USER_PROFILE_IMAGE_PATH', '/profile/');
            Laravel::env('USER_PROFILE_IMAGE_URL', config('project.url') . 'spaces/profile/');

            Laravel::env('PRODUCT_IMAGE_PATH', '/products/');
            Laravel::env('PRODUCT_IMAGE_URL', config('project.url') . 'spaces/products/');

            Laravel::env('GALLERY_IMAGE_PATH', '/gallery/');
            Laravel::env('GALLERY_IMAGE_URL', config('project.url') . 'spaces/gallery/');

            Laravel::env('BANNER_IMAGE_PATH', '/banners/');
            Laravel::env('BANNER_IMAGE_URL', config('project.url') . 'spaces/banners/');

            Laravel::env('POPUP_IMAGE_PATH',  '/popup/');
            Laravel::env('POPUP_IMAGE_URL', config('project.url') . 'spaces/popup/');

            $this->comment('Database & Image Paths are set');

            $this->info('=========================');

            $this->info('We are starting Project Migration.....');

            \Artisan::call('migrate');

            $this->info('Project Migration Completed');

             \Artisan::call('db:seed', [
                '--class' => 'AdminsTableSeeder'
            ]);

            $this->info('Admin has been Setup');

            \Artisan::call('db:seed', [
                '--class' => 'CountriesTableSeeder'
            ]);

            \Artisan::call('db:seed', [
                '--class' => 'StatesTableSeeder'
            ]);

            \Artisan::call('db:seed', [
                '--class' => 'SupportCategoriesTableSeeder'
            ]);

            \Artisan::call('db:seed', [
                '--class' => 'FirstUsersTableSeeder'
            ]);

            $this->info('First User has been Setup');

            $this->info('=========================');

            $this->info('∆ Admin Panel Credentials ∆' . "\n" . 'Username: ' . $brand.'@tymk.dev ' . "\n" . 'Password: admin#' .$brand );

            $this->info('=========================');
            /* Change permission of Images Folder */

            exec('chmod -R 777 public/spaces/banners');
            exec('chmod -R 777 public/spaces/popup');
            exec('chmod -R 777 public/spaces/gallery');
            exec('chmod -R 777 public/spaces/products');
            exec('chmod -R 777 public/spaces/profile');

            Laravel::env('APP_ENV', $development == 'y' ? 'local' : 'production');
            Laravel::env('APP_DEBUG', $development == 'y' ? 'true' : 'false');

            if ($development != 'y')
                $this->warn('Project Environment is ====> Production <====');

        }
    }
}
