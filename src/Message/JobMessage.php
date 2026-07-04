<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class JobMessage
{
    public function __construct(
        private string $jobId,
    ) {
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }
}
