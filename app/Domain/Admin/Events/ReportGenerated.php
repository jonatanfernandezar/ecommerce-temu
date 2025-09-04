<?php

namespace Domain\Admin\Events;

use Domain\Admin\ValueObjects\AdminId;

class ReportGenerated
{
    public function __construct(
        public readonly AdminId $adminId,
        public readonly string $reportId
    ) {}
}
