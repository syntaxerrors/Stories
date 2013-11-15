<?php

class HomeController extends BaseController {

    public function getIndex()
    {
        $developer = $this->hasPermission('DEVELOPER');

        if ($developer) {
            $this->addSubMenu('Add News', 'news/add');
        }

        $newsItems = Forum_Post::with('author')->where('frontPageFlag', 1)->orderBy('created_at', 'DESC')->get();

        $this->setViewData('newsItems', $newsItems);
        $this->setViewData('developer', $developer);
    }
}