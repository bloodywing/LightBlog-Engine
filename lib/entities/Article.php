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
            'Tags'   => $this->tags
        );
        return $collection->insert($self, array('safe' => true));
        
        
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
        return $collection->find()->sort(array('_id' => 0));
    }

    /**
     *
     * @param \Silex\Application $app
     * @return \Doctrine\MongoDB\Cursor
     */
    public function getRange(\Silex\Application $app, $range) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->find()->sort(array('_id' => 0))->limit(ARTICLE_LIMIT)->skip($range * ARTICLE_LIMIT);
    }

    
    public function getOne(\Silex\Application $app, $title) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->findOne(array('Title' => $title));
    }
    
    public function delArticle(\Silex\Application $app, $id) {
        $db = $app['mongodb'];
        
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        var_dump($collection->remove(array('_id' => new \MongoId($id))));
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


}

?>
