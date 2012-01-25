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
            $email;
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
            'Name' => $this->name,
            'Author'  => $this->author,
            'Password'   => crypt($this->password, SALT),
            'Email'  => $this->email
        );
        $collection->insert($self);
    }

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

}

?>
