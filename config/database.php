<?php
/**
 * Datenbank-Verbindung und Funktionen
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection = null;
    
    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Datenbankverbindung fehlgeschlagen: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset(DB_CHARSET);
            $this->connection->query("SET time_zone='+01:00'");
            
        } catch (Exception $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
    
    /**
     * Singleton-Pattern: Nur eine DB-Instanz
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Gibt die mysqli-Verbindung zurück
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prepared Statement ausführen
     * @param string $query SQL-Query mit ? Platzhaltern
     * @param array $params Array mit Parametern
     * @param string $types Datentypen (s=string, i=integer, d=double, b=blob)
     * @return mysqli_result|bool
     */
    public function prepare($query, $params = [], $types = '') {
        try {
            if (LOG_QUERIES) {
                file_put_contents(LOG_DIR . 'queries.log', 
                    date('[Y-m-d H:i:s]') . " Query: $query | Params: " . json_encode($params) . "\n", 
                    FILE_APPEND);
            }
            
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Prepare-Fehler: " . $this->connection->error);
            }
            
            // Parameter binden, falls vorhanden
            if (!empty($params)) {
                if (empty($types)) {
                    // Auto-detect types
                    $types = '';
                    foreach ($params as $param) {
                        if (is_int($param)) {
                            $types .= 'i';
                        } elseif (is_float($param)) {
                            $types .= 'd';
                        } else {
                            $types .= 's';
                        }
                    }
                }
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Execute-Fehler: " . $stmt->error);
            }
            
            return $stmt;
            
        } catch (Exception $e) {
            if (LOG_ERRORS) {
                file_put_contents(LOG_DIR . 'php_errors.log', 
                    date('[Y-m-d H:i:s]') . " DB-Fehler: " . $e->getMessage() . "\n", 
                    FILE_APPEND);
            }
            throw $e;
        }
    }
    
    /**
     * Ergebnis als assoziatives Array
     */
    public function fetchAll($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }
    
    /**
     * Nur eine Zeile
     */
    public function fetchOne($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
    
    /**
     * Nur ein Wert
     */
    public function fetchValue($query, $params = []) {
        $result = $this->fetchOne($query, $params);
        return $result ? reset($result) : null;
    }
    
    /**
     * INSERT/UPDATE/DELETE ausführen
     */
    public function execute($query, $params = []) {
        $stmt = $this->prepare($query, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
    
    /**
     * Letzte Insert-ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Transaktion starten
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Transaktion committen
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Transaktion rollback
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Fehler abfragen
     */
    public function getError() {
        return $this->connection->error;
    }
}

// Globale DB-Instanz
$db = Database::getInstance();
