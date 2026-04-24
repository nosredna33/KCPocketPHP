<?php

namespace KCPocket\Util;

class Database
{
    private static ?\PDO $pdo = null;
    private static string $dbPath = __DIR__ . '/../../data/kcpocket.db';

    private function __construct() {}

    public static function getConnection(): \PDO
    {
        if (self::$pdo === null) {
            try {
                // Ensure the data directory exists
                $dataDir = dirname(self::$dbPath);
                if (!is_dir($dataDir)) {
                    mkdir($dataDir, 0777, true);
                }
                self::$pdo = new \PDO('sqlite:' . self::$dbPath);
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                throw new \Exception('Could not connect to the database.');
            }
        }
        return self::$pdo;
    }

    public static function executeSqlFile(string $sqlFilePath): void
    {
        $pdo = self::getConnection();
        $sql = file_get_contents($sqlFilePath);
        if ($sql === false) {
            throw new \Exception('Could not read SQL file: ' . $sqlFilePath);
        }
        $pdo->exec($sql);
    }
}
