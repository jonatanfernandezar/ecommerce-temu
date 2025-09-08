<?php

namespace Application\Admin\DTO;

use DateTimeImmutable;

/**
 * DTO used to return admin report info to the application/UI layer.
 *
 * @param array<string, mixed> $data
 */
final class AdminReportDTO
{
    /**
     * @param array<string,mixed> $data
     */
    public function __construct(
        public readonly string $reportId,
        public readonly string $generatedByAdminId,
        public readonly string $type,
        public readonly array $data,
        public readonly string $generatedAtIso
    ) {}

    /**
     * Build from domain AdminReport entity.
     *
     * @param \Domain\Admin\Entities\AdminReport $report
     */
    public static function fromDomain(\Domain\Admin\Entities\AdminReport $report): self
    {
        return new self(
            $report->reportId(),
            $report->generatedBy()->value(),
            $report->type(),
            $report->data(),
            $report->generatedAt()->format(\DateTime::ATOM)
        );
    }
}
