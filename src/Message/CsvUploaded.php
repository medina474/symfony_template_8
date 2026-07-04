<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final readonly class CsvUploaded
{
    public function __construct(
        public string $importId,
        public string $content,
    ) {
    }
}
