<?php

namespace App\Service;

use App\Repository\CategoryRepository;



class CategoryService
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * This function allow to find all the categories in the BDD for display in the menu of navigation 
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    
}
