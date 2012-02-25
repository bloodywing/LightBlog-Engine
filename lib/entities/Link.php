<?php

namespace entities;

/**
 * Lets rescue Zelda
 * 
 * @TODO make the Link class more useful
 * @author pierre
 */
class Link {

    private $id,
            $target,
            $title,
            $category;
    
    protected $limit = 5;

    public function save($app) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'links');
        $self = array(
            'Target' => $this->getTarget(),
            'Title' => $this->getTitle(),
            'Category' => $this->getCategory()
        );
        return $collection->update(array('_id' => new \MongoId($this->getId())), $self, array('upsert' => true));
        
    }
    
    public function delLink(\Silex\Application $app, $id) {
        $db = $app['mongodb'];
        
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'links');
        return $collection->remove(array('_id' => new \MongoId($id)));
    }
    
    /**
     * This is almost the same thats used in entities\Articles 
     * @param \Silex\Application $app
     * @param integer $range
     * @param Array $findby
     * @return mixed 
     */
    public function getRange(\Silex\Application $app, $range, $findby = array()) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'links');
        return $collection->find($findby)->sort(array('Title' => -1))->limit($this->getLimit())->skip($range * $this->getLimit());
    }

    public function getTarget() {
        return $this->target;
    }

    public function setTarget($target) {
        $this->target = $target;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }
    
    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        if($limit < 0) {
            $limit = 1;
        }
        $this->limit = $limit;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }



}

?>
