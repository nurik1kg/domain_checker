<?php

namespace DomainChecker;

use Exception;
use MongoDB\BSON\PackedArray;

class DomainChecker {
    private array $domains;
    private \DateTime $currentDate;
    private int $notificationThresholdDays;

    public function __construct(array $domains, int $notificationThresholdDays = 30) {
        $this->domains = $domains;
        $this->currentDate = new \DateTime();
        $this->notificationThresholdDays = $notificationThresholdDays;
    }

    public function checkDomains(): void {
        foreach ($this->domains as $domain) {
            if ($domain->daysUntilExpiration($this->currentDate) <= $this->notificationThresholdDays) {
                $this->notify($domain);
            }
        }
    }

    private function notify(Domain $domain): void {
        echo "Домен {$domain->getName()} действует до " . $domain->getExpireDate()->format('D M d Y') . ", нужно произвести продление действия домена!\n";
    }

    public static function fromFile(string $filename): self {
        if (!file_exists($filename)) {
            throw new Exception("File not found: {$filename}");
        }

        $domains = [];
        $file = fopen($filename, 'r');
        if ($file) {
            while (($line = fgets($file)) !== false) {
                $data = explode(',', trim($line));
                if (count($data) === 2) {
                    $domains[] = new Domain(trim($data[0]), trim($data[1]));
                }
                else {
                    throw new Exception("Error reading data in file : {$filename}");
                }
            }
            fclose($file);
        }
        return new self($domains);
    }
}
