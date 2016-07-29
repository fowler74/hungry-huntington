<?php namespace Wappr;

class Api extends HungryHuntington {
    protected $db;
    public function __construct() {
        parent::__construct();
        $this->db = parent::getDb();
    }

    public function getDealsForDowGrouped($dow) {
        $grouped = array();
        $deals = array();
        $query = 'SELECT d.headline, d.url_title as deal_url, d.description, c.name,
            c.google_map, c.website, c.phone, c.address, t.type_of_deal,
            c.directions, c.url_title
            FROM deals d
            JOIN companies c
            ON c.company_id = d.company_id
            LEFT JOIN types_of_deals t
            ON t.type_id = d.type_id
            JOIN dow_deal b
            ON d.id = b.deals_id_fk
            JOIN days_of_week dow
            ON dow.dow_id = b.dow_id_fk
            WHERE dow.dow_name = :dow
            AND d.deleted = 0
            ORDER BY name ASC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dow', $dow, \PDO::PARAM_STR);
        $stmt->execute();
        $deals = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // make the restaurant name the key of the array
        for($i=0;$i<count($deals);$i++) {
            $grouped[$deals[$i]['name']][] = $deals[$i];
        }
        return $grouped;
    }

    public function getTodaysDeals() {
        $grouped = array();
        $deals = array();
        $dow = date("l");
        $query = 'SELECT d.headline, d.url_title as deal_url, d.description, c.name,
            c.google_map, c.website, c.phone, c.address, t.type_of_deal,
            c.directions, c.url_title
            FROM deals d
            JOIN companies c
            ON c.company_id = d.company_id
            LEFT JOIN types_of_deals t
            ON t.type_id = d.type_id
            JOIN dow_deal b
            ON d.id = b.deals_id_fk
            JOIN days_of_week dow
            ON dow.dow_id = b.dow_id_fk
            WHERE dow.dow_name = :dow
            AND d.deleted = 0
            ORDER BY name ASC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dow', $dow, \PDO::PARAM_STR);
        $stmt->execute();
        $deals = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // make the restaurant name the key of the array
        for($i=0;$i<count($deals);$i++) {
            $grouped[$deals[$i]['name']][] = $deals[$i];
        }
        return $grouped;
    }
}
