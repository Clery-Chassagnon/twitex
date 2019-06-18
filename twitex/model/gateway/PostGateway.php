<?php

namespace model\gateway;

use app\src\App;

class PostGateway
{
    protected $app;
    /**
     * @var \PDO
     */
    private $conn;

    private $id;

    private $writerId;

    private $message;

    private $writingDate;

    private $nbReposts;

    private $nbLikes;

    private $postAndRepostDate;

    private $reposterUsername = null;

    public function __construct(App $app)
    {
        $this->app = $app;
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
    public function getWriterId()
    {
        return $this->writerId;
    }

    /**
     * @param mixed $writerId
     */
    public function setWriterId($writerId)
    {
        $this->writerId = $writerId;
    }


    /**
     * @return mixed
     */
    public function getWriterInfos()
    {
        return $this->app->getService('postFinder')->findOneById($this->writerId);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getNbReposts()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('SELECT count(userId) FROM user_post WHERE postId = :id AND hasRepost = 1');
        $executed = $query->execute([
            ':id' => $this->id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        $element = $element['count(userId)'];

        return $element;
    }

    /**
     * @return mixed
     */
    public function getNbLikes()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('SELECT count(userId) FROM user_post WHERE postId = :id AND hasLike = 1');
        $executed = $query->execute([
            ':id' => $this->id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);


        $element = $element['count(userId)'];
        return $element;
    }

    /**
     * @return mixed
     */
    public function getWritingDate()
    {
        return $this->writingDate;
    }

    /**
     * @param mixed $writingDate
     */
    public function setWritingDate($writingDate): void
    {
        $this->writingDate = $writingDate;
    }

    /**
     * @return mixed
     */
    public function getPostAndRepostDate()
    {
        return $this->postAndRepostDate;
    }

    /**
     * @param mixed $postAndRepostDate
     */
    public function setPostAndRepostDate($postAndRepostDate)
    {
        $this->postAndRepostDate = $postAndRepostDate;
    }

    /**
     * @return mixed
     */
    public function getReposterUsername()
    {
        return $this->reposterUsername;
    }

    /**
     * @param mixed $reposterUsername
     */
    public function setReposterUsername($id)
    {
        $query = $this->conn->prepare('SELECT u.username FROM user u WHERE u.id = :id ');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element === null) return;

        $this->reposterUsername = $element["username"];
    }

    public function isLiked(){
        return $this->app->getService('postFinder')->hasLike($this->id);
    }

    public function isReposted(){
        return $this->app->getService('postFinder')->hasRepost($this->id);
    }



    public function insert()
    {
        $date = date ( 'c');
        $writerId = $_SESSION['id'];
        $query = $this->conn->prepare('INSERT INTO post(message, writerId, writingDate) VALUES (:message, :writerId, :writingDate)');
        $executed = $query->execute([
            ':message' => $this->message,
            ':writerId' => $writerId,
            ':writingDate' => $date
        ]);

        if(!$executed) throw new \Error('Insert Failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function update()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE post SET message = :message WHERE id = :id');
        $executed = $query->execute([
            ':message' => $this->message,
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Update Failed');
    }

    public function delete()
    {
        if(!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM user_post WHERE postId = :id; DELETE FROM post WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if(!$executed) throw new \Error('Delete Failed');
    }

    public function hydrate(Array $element){
        $this->id = $element['id'];
        $this->message = $element['message'];
        $this->writerId = $element['writerId'];
        $this->writingDate = $element['writingDate'];
    }
}