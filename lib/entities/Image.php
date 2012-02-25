<?php

namespace entities;

/**
 * Description of File
 *
 * @author pierre
 */
class Image {
    private $id,
            $type,
            $filename,
            $data,
            $metadata = array();
    
    protected $limit = 10;
    
    public function save($app) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'files');
        
        $self = array(
            'Type' => $this->getType(),
            'Filename' => $this->getFilename(),
            'Data'  => $this->getData(),
            'Metadata' => $this->getMetadata()
        );
        return $collection->update(array('_id' => new \MongoId($this->getId())), $self, array('upsert' => true));
        
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
        $collection = $db->selectCollection(MONGODB, 'files');
        return $collection->find($findby)->sort(array('Filename' => -1))->limit($this->getLimit())->skip($range * $this->getLimit());
    }
    
    
    public function getOne($image) {
        global $app;
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'files');
        return $collection->findOne(array('Filename' => $image));
    }
    
    public function delImage(\Silex\Application $app, $id) {
        $db = $app['mongodb'];
        
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'files');
        return $collection->remove(array('_id' => new \MongoId($id)));
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }
    
    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = new \MongoBinData($data);
    }
    
    public function getMetadata() {
        return $this->metadata;
    }

    public function setMetadata($metadata) {
        $this->metadata = $metadata;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }
    
}

?>
