<?php namespace Wappr;

class Manager extends Controller {

    protected function add() {
        if(parent::checkManager) {
            $url_title = parent::sluggit(parent::post['headline']);
            $query = 'INSERT INTO deals
            (company_id, headline, url_title, description, type_id, added_by)
            VALUES
            (:company_id, :headline, :url_title, :description, :type_id, :added_by)';
            $stmt = parent::db->prepare($query);
            $stmt->bindParam(':company_id', parent::post['company_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':headline', parent::post['headline'], \PDO::PARAM_STR);
            $stmt->bindParam(':url_title', $url_title, \PDO::PARAM_STR);
            $stmt->bindParam(':description', parent::post['description'], \PDO::PARAM_STR);
            $stmt->bindParam(':type_id', parent::post['type'], \PDO::PARAM_INT);
            $stmt->bindParam(':added_by', parent::userId, \PDO::PARAM_INT);
            $result = $stmt->execute();
            parent::addDaysOfWeek(parent::db->lastInsertId());
            return $result;
        }
    }
}
