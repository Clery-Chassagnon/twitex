<?php

namespace app;

use controller\PostController;
use controller\ProfileController;
use app\src\App;

class Routing
{
    private $app;

    /**
     * Routing constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function setup()
    {

        $post = new PostController($this->app);
        $profile = new ProfileController($this->app);

        $this->app->get('/', [$profile, 'connexionHandler']);

        $this->app->post('/handleConnexion', [$profile, 'connexionDBHandler']);

        $this->app->get('/inscription', [$profile, 'inscriptionHandler']);

        $this->app->post('/handleInscription', [$profile, 'inscriptionDBHandler']);

        $this->app->get('/handleDisconnection', [$profile, 'disconnectionDBHandler']);

        $this->app->get('/handleFollow/(\d+)', [$profile, 'followDBHandler']);

        $this->app->get('/handleUnfollow/(\d+)', [$profile, 'unfollowDBHandler']);

        $this->app->get('/showFollowing/(\d+)', [$profile, 'showFollowingHandler']);

        $this->app->get('/showFollowers/(\d+)', [$profile, 'showFollowersHandler']);

        $this->app->get('/profile/(\w+)', [$profile, 'profileHandler']);

        $this->app->get('/timeline', [$post, 'timelineHandler']);

        $this->app->get('/handleLike/(\d+)', [$post, 'likeDBHandler']);

        $this->app->get('/handleUnlike/(\d+)', [$post, 'unlikeDBHandler']);

        $this->app->get('/handleRepost/(\d+)', [$post, 'repostDBHandler']);

        $this->app->get('/handleUnrepost/(\d+)', [$post, 'unrepostDBHandler']);

        $this->app->post('/search', [$post, 'searchDBHandler']);

        $this->app->post('/handlePost', [$post, 'postDBHandler']);

        $this->app->get('/PostUpdate/(\d+)', [$post, 'postUpdateHandler']);

        $this->app->post('/handlePostUpdate/(\d+)', [$post, 'postUpdateDBHandler']);

        $this->app->get('/handlePostDelete/(\d+)', [$post, 'postDeleteDBHandler']);

        $this->app->get('/profileUpdate', [$profile, 'profileUpdateHandler']);

        $this->app->post('/handleProfileUpdate', [$profile, 'profileUpdateDBHandler']);

        $this->app->get('/handleProfileDelete', [$profile, 'profileDeleteDBHandler']);

        $this->app->get('/highlights', [$post, 'highlightsHandler']);

    }
}