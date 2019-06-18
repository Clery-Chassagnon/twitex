<?php

namespace model\gateway;

use app\src\App;

if(session_status ()==1) session_start();

class UserGateway
{
    /**
     * @var \PDO
     */
    private $conn;

    private $id;

    private $username;

    private $login;

    private $password;

    private $email;

    private $biography;

    private $birthday;

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('SELECT registrationDate FROM user WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);


        return $element['registrationDate'];
    }


    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }

    /**
     *  @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param mixed $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getNbFollowers()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('SELECT count(user_id) FROM userfollow WHERE following_id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);


        $element = $element['count(user_id)'];

        return $element;


    }

    /**
     * @return mixed
     */
    public function getNbFollowing()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('SELECT count(following_id) FROM userfollow WHERE user_id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);


        $element = $element['count(following_id)'];

        return $element;
    }



    public function insert()
    {
        $date = date ( 'c');
        $query = $this->conn->prepare('INSERT INTO user (username, login, password, email, biography, registrationDate, birthday) VALUES (:username, :login, :password, :email, :biography, :date, :birthday)');
        $executed = $query->execute([
            ':username' => $this->username,
            ':login' => $this->login,
            ':password' => $this->password,
            ':email' => $this->email,
            ':biography' => $this->biography,
            ':date' => $date,
            ':birthday' => $this->birthday
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE user SET username = :username, login = :login, password = :password, email = :email, biography =:biography, birthday = :birthday WHERE id = :id');
        $executed = $query->execute([
            ':username' => $this->username,
            ':login' => $this->login,
            ':password' => $this->password,
            ':email' => $this->email,
            ':biography' => $this->biography,
            ':birthday' => $this->birthday,
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Update Failed');
    }

    public function delete()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM userfollow WHERE user_id = :id OR following_id = :id; DELETE FROM user_post WHERE userId = :id; DELETE FROM user WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Delete Failed');
    }

    public function hydrate(Array $element){
        $this->id = $element['id'];
        $this->username = $element['username'];
        $this->login = $element['login'];
        $this->password = $element['password'];
        $this->email = $element['email'];
        $this->biography = $element['biography'];
        $this->birthday = $element['birthday'];
    }

    public function isFollowing(){
        $id = $_SESSION['id'];
        $query = $this->conn->prepare('SELECT id FROM userfollow WHERE user_id = :user_id AND following_id = :following_id ');
        $query->execute([':user_id' => $id,
            ':following_id' => $this->id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element == null) return false;
        else return true;
    }
}