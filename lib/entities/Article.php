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
        $collection->insert($self);
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
        return $collection->find();
    }
    
    public function getOne(\Silex\Application $app, $title) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'articles');
        return $collection->findOne(array('Title' => $title));
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
