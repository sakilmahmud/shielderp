<?php
// application/hooks/custom_hooks.php


// Define the log file path based on the application's path
$log_file = __DIR__ . '/application/logs/task_log.txt';

// Your custom task code here
function run_custom_task() {
    // Your task logic here
    $log_message = "Task executed at " . date('Y-m-d H:i:s');
    file_put_contents($GLOBALS['log_file'], $log_message . PHP_EOL, FILE_APPEND);
}


// Define a hook to run the custom task before every controller method
$hook['pre_controller'] = function() {
    run_custom_task();
};
