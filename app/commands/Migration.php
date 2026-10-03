<?php

class Migration
{
    public static $command = 'migration';
    public static $description = 'Run LavaLust database migrations';
    public static $arguments = [
        '[action]' => 'Action: run, create-migration, rollback, rollback-all, refresh, status',
        '[name]' => 'Migration name for create-migration',
    ];

    private static $routes = [
        'run' => '/_cli/migration/run',
        'rollback' => '/_cli/migration/rollback',
        'rollback-all' => '/_cli/migration/rollback-all',
        'refresh' => '/_cli/migration/refresh',
        'status' => '/_cli/migration/status',
    ];

    public function handle($action = null, array $flags = [], $name = null)
    {
        $action = $action ?: 'run';
        if ($action === 'create-migration') {
            if (!$name) {
                fwrite(STDERR, "Migration name is required. Example: php lava migration create-migration create_orders_table\n");
                exit(1);
            }
            $route = '/_cli/migration/create/' . rawurlencode($name);
        } elseif (isset(self::$routes[$action])) {
            $route = self::$routes[$action];
        } else {
            fwrite(STDERR, 'Unknown migration action. Use: ' . implode(', ', array_merge(array_keys(self::$routes), ['create-migration'])) . PHP_EOL);
            exit(1);
        }

        $runner = dirname(__DIR__, 2) . '/console/migration_runner.php';
        $command = escapeshellarg(PHP_BINARY) . ' '
            . escapeshellarg($runner) . ' '
            . escapeshellarg(ltrim($route, '/'));
        passthru($command, $status);
        if ($status !== 0) exit($status);
    }
}
