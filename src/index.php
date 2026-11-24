<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

echo "Hallo aus der Sandbox\n";
echo 'PHP ' . PHP_VERSION . ' auf ' . php_uname('n') . "\n";
echo date('c') . "\n";
