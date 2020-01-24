<?php

use VirtuaTechnology\Models\VirtuaTechnologyModel;

class Shopware_Controllers_Backend_VirtuaTechnology extends Shopware_Controllers_Backend_Application
{
    protected $model = VirtuaTechnologyModel::class;
    protected $alias = 'technology';
    public function save($data)
    {
        $slugService = $this->container->get('shopware.slug');
        $data['url'] = $slugService->slugify($data['name']);
        return parent::save($data);
    }
}
