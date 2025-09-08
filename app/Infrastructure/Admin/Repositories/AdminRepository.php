<?php

namespace Infrastructure\Admin\Repositories;

use Domain\Admin\Entities\Admin;
use Domain\Admin\ValueObjects\AdminId;
use Domain\Admin\ValueObjects\AdminRole;
use Domain\Admin\Entities\AdminReport;
use Infrastructure\Shared\Persistence\MySQLConnection;
use Domain\Admin\Repositories\AdminRepositoryInterface;

final class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(private MySQLConnection $connection) {}

    public function save(Admin $admin): void
    {
        $pdo = $this->connection::getConnection();

        $sql = "INSERT INTO admins (id, role) VALUES (:id, :role)
                ON DUPLICATE KEY UPDATE role = :role";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $admin->id()->value(),
            ':role' => $admin->role()->value()
        ]);
    }

    public function findById(AdminId $id): ?Admin
    {
        $pdo = $this->connection::getConnection();

        $sql = "SELECT id, role FROM admins WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Admin(
            new AdminId($row['id']),
            new AdminRole($row['role'])
        );
    }

    public function delete(AdminId $id): void
    {
        $pdo = $this->connection::getConnection();

        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id->value()]);
    }

    /**
     * @return AdminReport[]
     */
    public function findReportsByAdmin(AdminId $id): array
    {
        $pdo = $this->connection::getConnection();

        $sql = "SELECT report_id, type, generated_at, data_json 
                FROM admin_reports 
                WHERE generated_by = :adminId
                ORDER BY generated_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':adminId' => $id->value()]);
        $rows = $stmt->fetchAll();

        $reports = [];
        foreach ($rows as $row) {
            $reports[] = new AdminReport(
                $row['report_id'],
                $id,
                $row['type'],
                json_decode($row['data_json'], true)
            );
        }

        return $reports;
    }
}
