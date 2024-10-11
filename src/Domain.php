<?php

namespace DomainChecker;

use Exception;

class Domain {
    private string $name;
    private \DateTime $expireDate;

    public function __construct(string $name, string $expireDateStr) {
        $this->name = $name;
        $this->expireDate = \DateTime::createFromFormat('D M d Y', $expireDateStr);
        if (!$this->expireDate) {
            throw new Exception("Invalid date format for domain {$this->name}");
        }
    }

    public function getName(): string {
        return $this->name;
    }

    public function getExpireDate(): \DateTime {
        return $this->expireDate;
    }

    public function daysUntilExpiration(\DateTime $currentDate): int {
        return $this->expireDate->diff($currentDate)->days * ($currentDate < $this->expireDate ? 1 : -1);
    }
}
