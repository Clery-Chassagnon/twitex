<?php

namespace controller;

use controller\ControllerBase;
use app\src\App;
use app\src\request\Request;

class PostController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function timelineHandler(Request $request){
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function unlikeDBHandler(Request $request, $id){
        $this->app->getService('postFinder')->unlike($id);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function likeDBHandler(Request $request, $id){
        $this->app->getService('postFinder')->like($id);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function unrepostDBHandler(Request $request, $id){
        $this->app->getService('postFinder')->unrepost($id);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function repostDBHandler(Request $request, $id){
        $this->app->getService('postFinder')->repost($id);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function searchDBHandler(Request $request){
        $recherche = htmlspecialchars($request->getParameters('recherche'));
        if ($recherche == htmlspecialchars(stristr($request->getParameters('recherche'), '@')))
        {
            $recherches = $this->app->getService('postFinder')->searchAccounts($recherche);
            return $this->render('search', ["recherche" => $recherche, "accounts"=>$recherches]);
        }
        else $recherches = $this->app->getService('postFinder')->search($recherche);
        return $this->render('search', ["recherche" => $recherche, "posts"=>$recherches]);
    }

    public function postDBHandler(Request $request){
        $message = htmlspecialchars($request->getParameters('message'));
        $this->app->getService('postFinder')->post($message);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function postUpdateHandler(Request $request, $id){
        $post = $this->app->getService('postFinder')->getPost($id);
        return $this->render('updatePost', ['post'=>$post]);
    }

    public function postUpdateDBHandler(Request $request){
        $post = [
            'id' => htmlspecialchars($request->getParameters('postId')),
            'message' => htmlspecialchars($request->getParameters('message'))
            ];
        $this->app->getService('postFinder')->updatePost($post);
        $account = $this->app->getService('postFinder')->profile($_SESSION['username']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccount($account->getId());
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['account'=>$account, 'posts'=>$posts]);
    }

    public function postDeleteDBHandler(Request $request, $id){
        $this->app->getService('postFinder')->deletePost($id);
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getPostsFromAccounts($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('timeline', ['posts'=>$posts]);
    }

    public function highlightsHandler(Request $request){
        $followings = $this->app->getService('postFinder')->getFollowings($_SESSION['id']);
        $posts = $this->app->getService('postFinder')->getHighlights($followings);
        if(!is_array($posts) && $posts != null)$posts[] = $posts;
        return $this->render('highlights', ['posts'=>$posts]);
    }
}