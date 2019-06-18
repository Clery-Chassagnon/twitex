<?php

namespace model\finder;

interface FinderInterface{
    /*public function findAll();
    public function findOneById($id);
    public function search($searchString);
    */
    public function getFollowers($id);
    public function getFollowings($id);
    public function connect($infos);
    public function inscription($infos);
}