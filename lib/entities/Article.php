<?php

namespace entities;

/**
 * Description of Article
 *
 * @author pierre
 */
class Article {
    private $id,
            $author,
            $title,
            $body,
            $date,
            $category = "Default",
            $tags = array();
    
    /**
     *
     * @param \Silex\Application $app
     */
    public function save($app) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        $self = array(
            'Author' => $this->author,
            'Title'  => $this->title,
            'Body'   => $this->body,
            'Date'   => $this->date,
            'Category' => $this->category,
            'Tags'   => $this->tags
        );
        return $collection->update(array('_id' => new \MongoId($this->getId())), $self, array('upsert' => true));
        
        
    }
    
    /**
     *
     * @param \Silex\Application $app
     * @return \Doctrine\MongoDB\Cursor
     */
    public function getAll(\Silex\Application $app) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->find()->sort(array('Date' => 0));
    }

    /**
     *
     * @param \Silex\Application $app
     * @return \Doctrine\MongoDB\Cursor
     */
    public function getRange(\Silex\Application $app, $range, $findby = array()) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->find($findby)->sort(array('Date' => -1))->limit(ARTICLE_LIMIT)->skip($range * ARTICLE_LIMIT);
    }

    
    /**
     * Gets a single Article
     * @param \Silex\Application $app
     * @param mixed $field Fieldname in your MongoDB
     * @param mixed $term Term to search for
     * @return mixed 
     */
    public function getOne(\Silex\Application $app, $field, $term) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->findOne(array($field => $term));
    }
    
    public function delArticle(\Silex\Application $app, $id) {
        $db = $app['mongodb'];
        
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->remove(array('_id' => new \MongoId($id)));
    }


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }


}

?>
