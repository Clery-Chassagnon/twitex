<?php

namespace model\finder;
use model\gateway\PostGateway;
use model\gateway\UserGateway;
use model\finder\FinderInterface;
use app\src\App;

if(session_status ()==1) session_start();

class PostFinder implements FinderInterface
{

    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->getConnection();
    }

    public function connect($infos){
        $query = $this->conn->prepare('SELECT u.id, u.email, u.password, u.login, u.username, u.biography, u.birthday FROM user u WHERE u.email = :email ');
        $query->execute([':email' => $infos['email']]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element === null) return null;

        $account = new UserGateway($this->app);
        $account->hydrate($element);

        return $account;
    }

    public function inscription($infos)
    {
        $account = new UserGateway($this->app);
        $account->setUsername(htmlspecialchars($infos['username']));
        $account->setLogin(htmlspecialchars($infos['login']));
        $account->setPassword(md5($infos['password']));
        $account->setEmail(htmlspecialchars($infos['email']));
        $account->setBiography(htmlspecialchars($infos['biography']));
        $account->setBirthday($infos['birthday']);
        $account->insert();
    }

    public function verifyUsername($infos){
        $query = $this->conn->prepare('SELECT u.username FROM user u WHERE u.username = :username ');
        $query->execute([':username' => $infos['username']]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element['username'] == $infos['username']) return false;
        else return true;
    }

    public function verifyLogin($infos){
        $query = $this->conn->prepare('SELECT u.login FROM user u WHERE u.login = :login ');
        $query->execute([':login' => $infos['login']]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element['login'] == $infos['login']) return false;
        else return true;
    }

    public function verifyEmail($infos){
        $query = $this->conn->prepare('SELECT u.email FROM user u WHERE u.email = :email ');
        $query->execute([':email' => $infos['email']]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element['email'] == $infos['email']) return false;
        else return true;
    }

    public function verifyPassword($infos){
        $query = $this->conn->prepare('SELECT u.password FROM user u WHERE u.email = :email ');
        $query->execute([':email' => $infos['email']]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if ($element['password'] == md5($infos['password'])) return false;
        else return true;
    }


    public function getFollowings($id){
        $query = $this->conn->prepare('SELECT u.following_id FROM userfollow u WHERE u.user_id = :id ');
        $query->execute([':id' => $id]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $accounts = [];

        foreach ($elements as $id){
            $accounts[] = $this->findOneById($id["following_id"]);
        }

        return $accounts;
    }

    public function getFollowers($id){
        $query = $this->conn->prepare('SELECT u.user_id FROM userfollow u WHERE u.following_id = :id ');
        $query->execute([':id' => $id]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $accounts = [];

        foreach ($elements as $id){
            $accounts[] = $this->findOneById($id["user_id"]);
        }

        return $accounts;
    }

    public function profile($name){
        $query = $this->conn->prepare('SELECT u.id, u.email, u.password, u.login, u.username, u.biography, u.birthday FROM user u WHERE u.username = :name ');
        $query->execute([':name' => $name]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element == null) return null;

        $account = new UserGateway($this->app);
        $account->hydrate($element);

        return $account;
    }

    public function findOneById($id){
        $query = $this->conn->prepare('SELECT u.id, u.email, u.password, u.login, u.username, u.biography, u.birthday FROM user u WHERE u.id = :id ');
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element === null) return null;

        $account = new UserGateway($this->app);
        $account->hydrate($element);

        return $account;
    }

    public function follow($id){
        $query = $this->conn->prepare('SELECT * FROM userfollow WHERE user_id = :user_id AND following_id = :following_id) ');
        $query->execute([':user_id' => $_SESSION['id'],
            ':following_id' => $id]); // Exécution de la requête
        $ele = $query->fetch(\PDO::FETCH_ASSOC);
        if($ele == null) {
            $query = $this->conn->prepare('INSERT INTO userfollow(user_id, following_id) VALUES (:user_id,:following_id) ');
            $query->execute([':user_id' => $_SESSION['id'],
                ':following_id' => $id]); // Exécution de la requête
            $query->fetch(\PDO::FETCH_ASSOC);
        }
    }

    public function unfollow($id){
        $query = $this->conn->prepare(' DELETE FROM userfollow WHERE user_id = :user_id AND following_id = :following_id ');
        $query->execute([':user_id' => $_SESSION['id'],
            ':following_id' => $id]); // Exécution de la requête
        $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPostsFromAccounts($accounts)
    {
        $elements = [];
        $query = $this->conn->prepare('SELECT id, message, writerId, writingDate FROM post WHERE writerId = :id '); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $_SESSION['id']]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);
        if ($element != null) $elements[] = $element;

        if ($accounts == null && !$elements == null) {
            $posts = [];
            $post = null;
            foreach ($elements as $element) {

                foreach ($element as $ele) {
                    $post = new PostGateway($this->app);
                    $post->hydrate($ele);

                    $post->setPostAndRepostDate($post->getWritingDate());

                    $posts[] = $post;
                }
            }

            if(count($posts) > 1) {
                usort(
                    $posts,
                    function ($x, $y) {
                        return strtotime($x->getPostAndRepostDate()) < strtotime($y->getPostAndRepostDate());
                    }
                );
            }


            return $posts;
        }
        elseif ($accounts == null && $elements == null) return null;


        if (!is_array($accounts)) $accounts[] = $accounts;
        foreach ($accounts as $account) {
            $id = $account->getId();
            $query = $this->conn->prepare('SELECT id, message, writerId, writingDate FROM post WHERE writerId = :id '); // Création de la requête + utilisation order by pour ne pas utiliser sort
            $query->execute([':id' => $id]); // Exécution de la requête
            $element = $query->fetchAll(\PDO::FETCH_ASSOC);
            if ($element != null) $elements[] = $element;
        }


        if (count($elements) !== 0) {
            $posts = [];
            $post = null;
            foreach ($elements as $element) {

                foreach ($element as $ele) {
                    $post = new PostGateway($this->app);
                    $post->hydrate($ele);

                    $post->setPostAndRepostDate($post->getWritingDate());

                    $posts[] = $post;
                }
            }
        }

        $elements = [];
        $query = $this->conn->prepare('SELECT user_post.postId, user_post.userId, user_post.dateRepost, post.id, post.message, post.writerId, post.writingDate FROM post LEFT JOIN user_post ON post.id = user_post.postId WHERE user_post.userId = :id AND user_post.hasRepost = 1');
        $query->execute([':id' => $_SESSION['id']]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);
        if ($element != null) $elements[] = $element;


        if (count($elements) !== 0) {
            if (!isset($posts)) $posts = [];

            $post = null;
            foreach ($elements as $element) {
                foreach ($element as $ele) {
                    $post = new PostGateway($this->app);
                    $post->hydrate($ele);
                    $post->setPostAndRepostDate($ele["dateRepost"]);
                    $post->setReposterUsername($ele["userId"]);

                    if (!in_array($post, $posts)) $posts[] = $post;
                }
            }
        }

        if (!is_array($accounts)) $accounts[] = $accounts;
        foreach ($accounts as $account) {
            $id = $account->getId();
            $query = $this->conn->prepare('SELECT user_post.postId, user_post.userId, user_post.dateRepost, post.id, post.message, post.writerId, post.writingDate FROM post LEFT JOIN user_post ON post.id = user_post.postId WHERE user_post.userId = :id AND user_post.hasRepost = 1');
            $query->execute([':id' => $id]); // Exécution de la requête
            $element = $query->fetchAll(\PDO::FETCH_ASSOC);
            if ($element != null) $elements[] = $element;
        }

        if (count($elements) !== 0) {
            if (!isset($posts)) $posts = [];

            $post = null;
            foreach ($elements as $element) {
                foreach ($element as $ele) {
                    $post = new PostGateway($this->app);
                    $post->hydrate($ele);
                    $post->setPostAndRepostDate($ele["dateRepost"]);
                    $post->setReposterUsername($ele["userId"]);

                    if (!in_array($post, $posts)) $posts[] = $post;
                }
            }
        }

        if (!isset($posts)) return null;
        if(count($posts) > 1) {
            usort(
                $posts,
                function ($x, $y) {
                    return strtotime($x->getPostAndRepostDate()) < strtotime($y->getPostAndRepostDate());
                }
            );
        }


        return $posts;
    }

    public function getPostsFromAccount($id)
    {
        $query = $this->conn->prepare('SELECT id, message, writerId, writingDate FROM post WHERE writerId = :id '); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);



        if (count($elements) !== 0) {
            $posts = [];
            $post = null;
                foreach ($elements as $ele) {
                    $post = new PostGateway($this->app);
                    $post->hydrate($ele);

                    $post->setPostAndRepostDate($post->getWritingDate());

                    $posts[] = $post;

            }
        }

        $elements = [];
        $query = $this->conn->prepare('SELECT user_post.postId, user_post.userId, user_post.dateRepost, post.id, post.message, post.writerId, post.writingDate FROM post LEFT JOIN user_post ON post.id = user_post.postId WHERE user_post.userId = :id AND user_post.hasRepost = 1');
        $query->execute([':id' => $id]); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($elements) !== 0) {
            if (!isset($posts)) $posts = [];

            $post = null;
            foreach ($elements as $ele) {
                $post = new PostGateway($this->app);
                $post->hydrate($ele);
                $post->setPostAndRepostDate($ele["dateRepost"]);
                $post->setReposterUsername($ele["userId"]);

                    if (!in_array($post, $posts)) $posts[] = $post;

            }
        }

        if (!isset($posts)) return null;
        if(count($posts) > 1) {
            usort(
                $posts,
                function ($x, $y) {
                    return strtotime($x->getPostAndRepostDate()) < strtotime($y->getPostAndRepostDate());
                }
            );
        }


        return $posts;
    }

    public function hasRepost($postId){
        $id = $_SESSION['id'];
        $query = $this->conn->prepare('SELECT dateRepost FROM user_post WHERE userId = :userId AND postId = :postId AND hasRepost = 1 ');
        $query->execute([':userId' => $id,
            ':postId' => $postId]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element == null) return null;
        else return $element;
    }

    public function hasLike($postId){
        $id = $_SESSION['id'];
        $query = $this->conn->prepare('SELECT dateLike FROM user_post WHERE userId = :userId AND postId = :postId AND hasLike = 1 ');
        $query->execute([':userId' => $id,
            ':postId' => $postId]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element == null) return null;
        else return $element;
    }

    public function like($id){
        $date = date ( 'c');
        $userId = $_SESSION['id'];
        $query = $this->conn->prepare('SELECT * FROM user_post WHERE userId = :userId AND postId = :postId ');
        $query->execute([':userId' => $userId,
            ':postId' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element != null){
            $query = $this->conn->prepare('UPDATE user_post SET hasLike = 1, dateLike = :date WHERE userId = :userId AND postId = :postId');
            $executed = $query->execute([':userId' => $userId,
                ':postId' => $id,
                ':date' => $date
            ]);
        }
        else {
            $query = $this->conn->prepare(' INSERT INTO user_post(userId, postId, hasLike, dateLike) VALUES (:userId,:postId, 1, :date) ');
            $query->execute([':userId' => $userId,
                ':postId' => $id,
                ':date' => $date]); // Exécution de la requête
            $query->fetch(\PDO::FETCH_ASSOC);
        }
    }

    public function unlike($id){
        $userId = $_SESSION['id'];
        $query = $this->conn->prepare('UPDATE user_post SET hasLike = 0, dateLike = "0000-00-00 00:00:00" WHERE userId = :userId AND postId = :postId');
        $executed = $query->execute([':userId' => $userId,
            ':postId' => $id
        ]);
    }

    public function repost($id){
        $date = date ( 'c');
        $userId = $_SESSION['id'];
        $query = $this->conn->prepare('SELECT * FROM user_post WHERE userId = :userId AND postId = :postId');
        $query->execute([':userId' => $userId,
            ':postId' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if($element != null){
            $query = $this->conn->prepare('UPDATE user_post SET hasRepost = 1, dateRepost = :date WHERE userId = :userId AND postId = :postId');
            $executed = $query->execute([':userId' => $userId,
                ':postId' => $id,
                ':date' => $date
            ]);
        }
        else {
            $query = $this->conn->prepare(' INSERT INTO user_post(userId, postId, hasRepost, dateRepost) VALUES (:userId,:postId, 1, :date) ');
            $query->execute([':userId' => $userId,
                ':postId' => $id,
                ':date' => $date]); // Exécution de la requête
            $query->fetch(\PDO::FETCH_ASSOC);
        }
    }

    public function unrepost($id){
        $userId = $_SESSION['id'];
        $query = $this->conn->prepare('UPDATE user_post SET hasRepost = 0, dateRepost = "0000-00-00 00:00:00" WHERE userId = :userId AND postId = :postId');
        $executed = $query->execute([':userId' => $userId,
            ':postId' => $id
        ]);
    }

    public function searchAccounts($searchString) {
        $query = $this->conn->prepare('SELECT u.id, u.email, u.password, u.login, u.username, u.biography, u.birthday FROM user u WHERE u.login like :login '); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':login' => '%' . $searchString .  '%']); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $accounts = [];
        $account = null;
        foreach ($elements as $element){
            $account = new UserGateway($this->app);
            $account->hydrate($element);

            $accounts[] = $account;
        }

        return $accounts;
    }

    public function search($searchString) {
        $query = $this->conn->prepare('SELECT id, message, writerId, writingDate FROM post WHERE message like :message'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':message' => '%' . $searchString .  '%']); // Exécution de la requête
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if(count($elements) === 0) return null;

        $posts = [];
        $post = null;
        foreach ($elements as $element){

            $post = new PostGateway($this->app);
            $post->hydrate($element);

            $posts[] = $post;
        }

        return $posts;
    }

    public function getPost($id){
        $query = $this->conn->prepare('SELECT id, message, writerId, writingDate FROM post WHERE id = :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if($element == null) return null;

        $post = new PostGateway($this->app);
        $post->hydrate($element);

        return $post;
    }

    public function post($message)
    {
        $post = new PostGateway($this->app);
        $post->setMessage(htmlspecialchars($message));
        $post->setWriterId($_SESSION['id']);
        $post->insert();
    }

    public function updatePost($infos)
    {
        $post = $this->getPost($infos['id']);
        $post->setMessage(htmlspecialchars($infos['message']));
        $post->setWriterId($_SESSION['id']);
        $post->update();
    }

    public function deletePost($id)
    {
        $post = $this->getPost($id);
        $post->delete();
    }

    public function updateProfile(Array $infos)
    {
        $profile = $this->findOneById($_SESSION['id']);
        $profile->setPassword(md5($infos['password']));
        $profile->setEmail(htmlspecialchars($infos['email']));
        $profile->setBiography(htmlspecialchars($infos['biography']));
        $profile->setBirthday($infos['birthday']);
        $profile->setUsername(htmlspecialchars($infos['username']));
        $profile->update();
    }

    public function deleteProfile(Array $infos)
    {
        $account = $this->findOneById($_SESSION['id']);
        $posts = $this->getPostsFromAccount($account->getId());
        foreach ($posts as $post){
            if($post->getReposterUsername() == null) $this->deletePost($post->getId());
        }
        $account->delete();
    }

    public function getHighlights($accounts){
        $postsToSort = $this->getPostsFromAccounts($accounts);
        if(!is_array($postsToSort) && $postsToSort != null)$postsToSort[] = $postsToSort;
        else if($postsToSort == null) return null;
        $date = date ( 'c');
        $date = date('Y-m-d', strtotime($date.' -1 days'));
        $posts = [];
        foreach ($postsToSort as $post){
            $datePost = date('Y-m-d', strtotime($post->getWritingDate()));
            if ($datePost == $date) $posts[] = $post;
        }

        if(count($posts) > 1) {
            usort(
                $posts,
                function ($x, $y) {
                    if ($x->getNbReposts() == $y->getNbReposts()) return $x->getNbLikes() < $y->getNbLikes();
                    else return $x->getNbReposts() < $y->getNbReposts();
                }
            );
        }

        if (count($posts) > 10)
        {
            for($i =0 ; $i< 10; $i++)
            $highlights[$i] = $posts[$i];
        }
        else $highlights = $posts;

        if ($highlights == null) return null;
        return $highlights;
    }

}