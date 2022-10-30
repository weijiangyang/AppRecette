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

    
    public function findAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    
}
