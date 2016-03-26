<?php namespace Wappr;


/**
 * Hungry Huntington
 *
 * Get all of the deals from the database and store them in a public property.
 *
 * @author Levi <levi@wappr.co>
 */
class HungryHuntington {
    protected $db;

    public function __construct() {
        $d = self::loadConfig();
        try {
            $this->db = new \PDO("mysql:host=" . $d['hostname'] . ";dbname="
                    . $d['database'] . ";charset=utf8",
                    $d['username'], $d['password']);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (Exception $ex) {
            echo $ex->getMessage() . "\n";
            die();
        }
    }

    /**
     * Get all the deals in Huntington
     * @return Array an associative array with all the deals
     */
    public function getDeals() {
        # Waiting on the data
        $query = 'SELECT id, name, headline, description,
        google_map, website, phone, address, `type`
        FROM deals
        ORDER BY name ASC
        LIMIT 1000';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Load the config file
     *
     * @return array configuration options for Hungry Huntington
     */
    public static function loadConfig() {
        return include(ROOT . DS . 'config.php');
    }
}
