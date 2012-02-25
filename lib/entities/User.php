<?php

namespace entities;

/**
 * Description of User
 *
 * @author pierre
 */
class User {
    private $name,
            $password,
            $author,
            $email,
            $access;
    /**
     *
     * @param Silex\Application $app 
     */
    public function save($app) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'Users');
        $self = array(
            'Name' => $this->getName(),
            'Author'  => $this->getAuthor(),
            'Password'   => crypt($this->getPassword(), SALT),
            'Email'  => $this->getEmail(),
            'Access' => $this->getAccess()
        );
        $collection->insert($self);
    }

    /**
     *
     * @param \Silex\Application $app
     * @param array $user
     * @return mixed 
     */
    public function getUserinfo(\Silex\Application $app, $user) {
        /* @var $db \Doctrine\MongoDB\Connection */
        $db =  $app['mongodb'];
        /* @var $collection \Doctrine\MongoDB\LoggableCollection */
        $collection = $db->selectCollection(MONGODB, 'Users');
        $userinfo = $collection->findOne(array('Name' => $user['Name'], 'Password' => crypt($user['Password'], SALT)), array('_id' => 0));
        return $userinfo;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getAccess() {
        return $this->access;
    }

    public function setAccess($access) {
        $this->access = $access;
    }

}

?>
