<?php

namespace entities;

/**
 * Description of Comment
 *
 * @author pierre
 */
class Comment {

    private $id,
            $author,
            $email,
            $body,
            $date;

    /**
     * @see Article
     * @return boolean 
     */
    public function save() {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db = $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'comments');
        $self = array(
            'Author' => $this->author,
            'Email' => $this->email,
            'Body' => $this->body,
            'Date' => $this->date,
        );
        return $collection->update(array('_id' => new \MongoId($this->getId())), $self, array('upsert' => true));
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



}

?>
