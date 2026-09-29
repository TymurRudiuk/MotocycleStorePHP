<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class MotorcycleRepository
{
    public function __construct(
        private readonly array $config
    ) {
    }

    public function isDatabaseAvailable(): bool
    {
        return Database::getConnection($this->config) instanceof PDO;
    }

    public function getFeatured(int $limit = 3): array
    {
        return $this->getAll([], $limit, 0);
    }

    public function getAll(array $filters = [], ?int $limit = null, int $offset = 0): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return [];
        }

        [$where, $params] = $this->buildFilters($filters);

        $sql = 'SELECT m.id, m.name, m.brand,
                       COALESCE(mc.name, m.type) AS type,
                       m.engine_volume, m.power, m.price, m.model_year, m.image, m.description,
                       m.class_id
                FROM motorcycles m
                LEFT JOIN motorcycle_classes mc ON mc.id = m.class_id
                WHERE ' . $where . '
                ORDER BY m.id DESC';

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, $limit) . ' OFFSET ' . max(0, $offset);
        }

        $statement = $connection->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll() ?: [];
    }

    public function countAll(array $filters = []): int
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return 0;
        }

        [$where, $params] = $this->buildFilters($filters);

        $statement = $connection->prepare(
            'SELECT COUNT(*) AS total
             FROM motorcycles m
             LEFT JOIN motorcycle_classes mc ON mc.id = m.class_id
             WHERE ' . $where
        );
        $statement->execute($params);
        $result = $statement->fetch();

        return (int) ($result['total'] ?? 0);
    }

    public function getFilterOptions(): array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return ['brands' => [], 'types' => []];
        }

        $brands = $connection->query(
            'SELECT DISTINCT brand FROM motorcycles ORDER BY brand ASC'
        )->fetchAll() ?: [];

        $types = $connection->query(
            'SELECT DISTINCT COALESCE(mc.name, m.type) AS type
             FROM motorcycles m
             LEFT JOIN motorcycle_classes mc ON mc.id = m.class_id
             ORDER BY type ASC'
        )->fetchAll() ?: [];

        return [
            'brands' => array_column($brands, 'brand'),
            'types' => array_values(array_filter(array_column($types, 'type'))),
        ];
    }

    public function findById(int $id): ?array
    {
        $connection = Database::getConnection($this->config);

        if (!$connection instanceof PDO) {
            return null;
        }

        $statement = $connection->prepare(
            'SELECT m.id, m.name, m.brand,
                    COALESCE(mc.name, m.type) AS type,
                    m.engine_volume, m.power, m.price, m.model_year, m.image, m.description,
                    m.class_id
             FROM motorcycles m
             LEFT JOIN motorcycle_classes mc ON mc.id = m.class_id
             WHERE m.id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $motorcycle = $statement->fetch();

        return $motorcycle !== false ? $motorcycle : null;
    }

    private function buildFilters(array $filters): array
    {
        $where = '1=1';
        $params = [];

        if (trim((string) ($filters['search'] ?? '')) !== '') {
            $where .= ' AND (m.name LIKE :search OR m.brand LIKE :search OR COALESCE(mc.name, m.type) LIKE :search)';
            $params['search'] = '%' . trim((string) $filters['search']) . '%';
        }

        if (trim((string) ($filters['brand'] ?? '')) !== '') {
            $where .= ' AND m.brand = :brand';
            $params['brand'] = trim((string) $filters['brand']);
        }

        if (trim((string) ($filters['type'] ?? '')) !== '') {
            $where .= ' AND COALESCE(mc.name, m.type) = :type';
            $params['type'] = trim((string) $filters['type']);
        }

        if (trim((string) ($filters['min_price'] ?? '')) !== '') {
            $where .= ' AND m.price >= :min_price';
            $params['min_price'] = (float) $filters['min_price'];
        }

        if (trim((string) ($filters['max_price'] ?? '')) !== '') {
            $where .= ' AND m.price <= :max_price';
            $params['max_price'] = (float) $filters['max_price'];
        }

        return [$where, $params];
    }
}
