<?php namespace Wappr;


/**
 * Hungry Huntington
 *
 * Get all of the deals from the database and store them in a public property.
 *
 * @author Levi <levi@wappr.co>
 * @version 1.1.0
 */
class HungryHuntington {
    public $version = '1.1.0';
    public $page;
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
        $query = 'SELECT id, c.name, headline, description,
        c.google_map, c.website, c.phone, c.address, t.type_of_deal
        FROM deals d
        LEFT JOIN companies c
        ON c.company_id = d.company_id
        LEFT JOIN types_of_deals t
        ON t.type_id = d.type_id
        WHERE d.deleted = 0
        ORDER BY c.name ASC
        LIMIT 1000';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCompanies() {
        $query = 'SELECT company_id, name, url_title
        FROM companies
        WHERE hidden = 0
        ORDER BY name ASC
        LIMIT 100';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get the name of the Restaurant
     *
     * Might use $this->pages['urlTitle'][1]
     *
     * @param  [string] $companyUrl [url-title-for-restaurant]
     * @return [string]             [Title for Restaurant]
     */
    public function getCompanyName($companyUrl) {
        $query = 'SELECT name
        FROM companies
        WHERE url_title = :url_title
        LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':url_title', $companyUrl, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data['name'];
    }

    /**
     * Get name of deal
     *
     * Might use $this->pages['urlTitle'][2]
     *
     * @param  [string] $dealUrl [url-title-for-title]
     * @return [string]          [Title for deal]
     */
    public function getDealName($dealUrl) {
        $query = 'SELECT headline
        FROM deals
        WHERE url_title = :url_title
        LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':url_title', $dealUrl, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data['headline'];
    }

    public function getCompanyDeals($companyUrl) {
        $query = 'SELECT d.headline, d.url_title, d.description, c.name,
        c.google_map, c.website, c.phone, c.address, t.type_of_deal
        FROM deals d
        JOIN companies c
        ON c.company_id = d.company_id
        LEFT JOIN types_of_deals t
        ON t.type_id = d.type_id
        WHERE d.deleted = 0
        AND c.url_title = :url_title';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':url_title', $companyUrl, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDealsStartingToday() {
        $order = $this->orderStartToday();
        $query = 'SELECT d.headline, dow.dow_name
            FROM deals d
            JOIN dow_deal b
            ON d.id = b.deals_id_fk
            JOIN days_of_week dow
            ON dow.dow_id = b.dow_id_fk
            #WHERE dow.dow_id IN (0, 1, 2, 3, 4, 5, 6)
            ORDER BY FIELD(dow.dow_id, ' . $order . ')';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTypes() {
        $query = 'SELECT type_id, type_of_deal
        FROM types_of_deals
        LIMIT 50';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function orderStartToday() {
        $daysOfWeek = [0, 1, 2, 3, 4, 5, 6];
        $order = '';
        $today = date("N") - 1;
        $i = 0;
        $notFound = true;
        while($notFound) {
            if($today == $daysOfWeek[$i]) {
            	// Found the start day
                $order = $i;
                $i++;
            }
            if($i < 7) {
            	// Find the rest of the days
            	$order .= ', ' . $i;
            	$i++;
            }
            if($i >= 7) {
            	for($x=0;$x<$today;$x++) {
            		$t = $i + $x;
            		$order .= ',' . $x;
            	}
            	$notFound = false;
            }
        }
        return $order;
    }

    public function getPageData($key = '') {
        if(isset($key)) {
            return $this->page[$key];
        } else {
            return $this->page;
        }
    }

    protected function getDb() {
        return $this->db;
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
