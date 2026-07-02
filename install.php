<?php
// Simple script to check if vendor exists and run composer install if not
$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    echo "Vendor already installed.\n";
    exit(0);
}
echo "Vendor not found. Running composer install...\n";
// Run composer install with output to file
$cmd = 'cd /d "' . __DIR__ . '" && composer install --no-interaction --prefer-dist --no-dev 2>&1';
$output = [];
$returnVar = 0;
exec($cmd, $output, $returnVar);
file_put_contents(__DIR__ . '/composer_install.log', implode("\n", $output));
if (file_exists($vendorAutoload)) {
    echo "SUCCESS: Vendor installed.\n";
} else {
    echo "FAILED with code: $returnVar\n";
    echo "Last output: " . implode("\n", array_slice($output, -20)) . "\n";
}
