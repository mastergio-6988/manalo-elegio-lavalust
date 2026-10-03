<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/** Migration actions are available only to a PHP CLI process. */
class MigrationController extends Controller
{
    private function migration()
    {
        if (PHP_SAPI !== 'cli') {
            http_response_code(404);
            exit('Not found');
        }
        return $this->call->library('migration');
    }

    public function run($action)
    {
        $migration = $this->migration();
        $actions = [
            'run' => 'migrate',
            'rollback' => 'rollback',
            'rollback-all' => 'rollback_all',
            'refresh' => 'refresh',
            'status' => 'status',
        ];
        if (!isset($actions[$action])) {
            fwrite(STDERR, "Unknown migration action: {$action}\n");
            exit(1);
        }
        $method = $actions[$action];
        $migration->$method();
    }

    public function create($migration_name)
    {
        $this->migration()->create_migration($migration_name);
    }
}
