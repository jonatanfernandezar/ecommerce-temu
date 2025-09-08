<?php

namespace Domain\Admin\Entities;

use Domain\Admin\ValueObjects\AdminId;

class AdminReport
{
    private string $reportId;
    private AdminId $generatedBy;
    private string $type;
    private \DateTimeImmutable $generatedAt;
    //private array $data;

    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
    */

    public function __construct(string $reportId, AdminId $generatedBy, string $type, array $data)
    {
        $this->reportId    = $reportId;
        $this->generatedBy = $generatedBy;
        $this->type        = $type;
        $this->generatedAt = new \DateTimeImmutable();
        $this->data        = $data;
    }

    public function reportId(): string
    {
        return $this->reportId;
    }
    public function generatedBy(): AdminId
    {
        return $this->generatedBy;
    }
    public function type(): string
    {
        return $this->type;
    }
    public function generatedAt(): \DateTimeImmutable
    {
        return $this->generatedAt;
    }

    /**
     * @return array<string, mixed>
    */
    public function data(): array
    {
        return $this->data;
    }
}
