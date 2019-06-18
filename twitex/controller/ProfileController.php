<?php

namespace controller;

use controller\ControllerBase;
use app\src\App;
use model\finder\PostFinder;
use model\gateway\PostGateway;
use app\src\request\Request;

class ProfileController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function connexionHandler(Request $request)
    {
        return $this->render('connexion');
    }

    public function connexionDBHandler(Request $request)
    {
        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $account = [
                'email' => htmlspecialchars(trim ( $request->getParameters('email')," \t\n\r\0\x0B" )),
                'password' => htmlspecialchars($request->getParameters('password'))
            ];

            if ($this->app->getService('postFinder')->verifyEmail($account)){$e = "This email isn't used";
                return $this->render('connexion', ["error"=>$e]);}

            if ($this->app->getService('postFinder')->verifyPassword($account)){$e = "Wrong password";
                return $this->render('connexion', ["error"=>$e]);}

            $account = $this->app->getService('postFinder')->connect($account);
            if (session_status() == 1) session_start();
            $_SESSION = [
                'id' => $account->getId(),
                'username' => $account->getUsername(),
                'login' => $account->getLogin(),
                'email' => $account->getEmail(),
                'biography' => $account->getBiography(),
                'birthday' => $account->getBirthday()
            ];

            $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
            $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
            if(!is_array($posts) && $posts != null)$posts[] = $posts;
            return $this->render('timeline', ['posts'=>$posts]);

        } catch (Exception $e) {
            return $this->render('connexion', ["error"=>$e]);
        }
    }

    public function inscriptionHandler(Request $request)
    {
        return $this->render('inscription');
    }

    public function inscriptionDBHandler(Request $request)
    {

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            if (htmlspecialchars(stristr($request->getParameters('login'), '@'))!=htmlspecialchars($request->getParameters('login'))) $login = '@' . $request->getParameters('login');
            else $login = trim ( $request->getParameters('login')," \t\n\r\0\x0B" );

            $account = [
                'username' => htmlspecialchars(trim ( $request->getParameters('username')," \t\n\r\0\x0B" )),
                'login' => htmlspecialchars($login),
                'email' => htmlspecialchars(trim ( $request->getParameters('email')," \t\n\r\0\x0B" )),
                'password' => htmlspecialchars($request->getParameters('password')),
                'biography' => htmlspecialchars($request->getParameters('biography')),
                'birthday' => htmlspecialchars($request->getParameters('birthday'))
            ];

            if ($account['username']&&$account['login']&&$account['email']&&$account['password']&&htmlspecialchars($request->getParameters('repeatPassword'))&&$account['biography']&&$account['birthday'])
            {


            if ($this->app->getService('postFinder')->verifyUsername($account)){}
            else {$e = "This username is already used";
                return $this->render('inscription', ["error"=>$e]);
            }

            if ($this->app->getService('postFinder')->verifyLogin($account)){}
            else {$e = "This login is already used";
                return $this->render('inscription', ["error"=>$e]);
            }


            if ($this->app->getService('postFinder')->verifyEmail($account)){}
            else {$e = "This email is already used";
                return $this->render('inscription', ["error"=>$e]);
            }

                if (strstr($account['email'], '@')){}
                else {$e = "Please use a proper email";
                    return $this->render('inscription', ["error"=>$e]);
                }



                        if ($account['password'] == htmlspecialchars($request->getParameters('repeatPassword'))) ;
                        else {
                            $e = "The passwords are different";
                            return $this->render('inscription', ["error" => $e]);
                        }

            }else {$e = "Please fill in all fields";
                return $this->render('inscription', ["error"=>$e]);
            }


            $this->app->getService('postFinder')->inscription($account);
            return $this->render('connexion');


        } catch (Exception $e) {
            $e = "Error during the inscription";
            return $this->render('inscription', ["error"=>$e]);
        }
    }

        public function disconnectionDBHandler(){
            if (session_status() == 1) session_start();
            $_SESSION = array();

            unset($_SESSION);

            session_destroy();

            return $this->render('connexion');
        }

        public function showFollowingHandler(Request $request, $id){
            $followings = $this->app->getService('postFinder')->getFollowings($id);
            return $this->render('following', ["followings"=>$followings]);
        }

        public function showFollowersHandler(Request $request, $id){
            $followers = $this->app->getService('postFinder')->getFollowers($id);
            return $this->render('followers', ["followers"=>$followers]);
        }

        public function unfollowDBHandler(Request $request, $id){
            $this->app->getService('postFinder')->unfollow($id);
            $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
            $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
            return $this->render('timeline', ['posts'=>$posts]);
        }

        public function followDBHandler(Request $request, $id){
            $this->app->getService('postFinder')->follow($id);
            $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
            $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
            return $this->render('timeline', ['posts'=>$posts]);
        }

        public function profileHandler(Request $request, $name){
            $account = $this->app->getService('postFinder')->profile($name);
            $posts = $this->app->getService('postFinder')->getPostsFromAccount($account->getId());
            if(!is_array($posts) && $posts != null)$posts[] = $posts;
            return $this->render('profile', ['account'=>$account, 'posts'=>$posts]);
        }

    public function profileUpdateHandler(Request $request)
    {
        return $this->render('profileUpdate');
    }

    public function profileUpdateDBHandler(Request $request)
    {

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $account = [
                'username' => htmlspecialchars(trim ( $request->getParameters('username')," \t\n\r\0\x0B" )),
                'email' => htmlspecialchars(trim ( $request->getParameters('email')," \t\n\r\0\x0B" )),
                'password' => htmlspecialchars($request->getParameters('password')),
                'biography' => htmlspecialchars($request->getParameters('biography')),
                'birthday' => htmlspecialchars($request->getParameters('birthday'))
            ];

            if ($account['username']&&$account['email']&&$account['password']&&htmlspecialchars($request->getParameters('repeatPassword'))&&$account['biography']&&$account['birthday'])
            {

                if ($account['email'] != $_SESSION['email'] && !$this->app->getService('postFinder')->verifyEmail($account)){$e = "This email is already used";
                    return $this->render('profileUpdate', ["error"=>$e]);
                }

                if ($account['email'] != $_SESSION['email'] && !strstr($account['email'], '@')){$e = "Please use a proper email";
                    return $this->render('profileUpdate', ["error"=>$e]);
                }

                if ($account['password'] == htmlspecialchars($request->getParameters('repeatPassword'))) ;
                else {
                    $e = "The passwords are different";
                    return $this->render('profileUpdate', ["error" => $e]);
                }

            }else {$e = "Please fill in all fields";
                return $this->render('profileUpdate', ["error"=>$e]);
            }
            $this->app->getService('postFinder')->updateProfile($account);
            $account = $this->app->getService('postFinder')->profile($account['username']);
            $_SESSION = [
                'id' => $account->getId(),
                'username' => $account->getUsername(),
                'login' => $account->getLogin(),
                'email' => $account->getEmail(),
                'biography' => $account->getBiography(),
                'birthday' => $account->getBirthday()
            ];
            $posts = $this->app->getService('postFinder')->getPostsFromAccount($account->getId());
            return $this->render('profile', ['account'=>$account, 'posts'=>$posts]);
        }
        catch (Exception $e) {
            $e = "Error during the modifications";
            return $this->render('profileUpdate', ["error"=>$e]);
        }
    }

    public function profileDeleteDBHandler(Request $request)
    {
        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $account = [
                'id' => $_SESSION['id']
            ];


            $this->app->getService('postFinder')->deleteProfile($account);
            return $this->render('connexion');

        } catch (Exception $e) {
            return $this->render('profile'); // On renvoie la city acutelle au template
        }
    }
}