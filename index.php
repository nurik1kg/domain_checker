<?php

require_once './src/Domain.php';
require_once './src/DomainChecker.php';

use DomainChecker\Domain;
use DomainChecker\DomainChecker;

try {
    $checker = DomainChecker::fromFile('domains.txt');
    $checker->checkDomains();
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
