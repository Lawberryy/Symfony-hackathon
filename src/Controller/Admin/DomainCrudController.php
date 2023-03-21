<?php

namespace App\Controller\Admin;

use App\Entity\Domain;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DomainCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Domain::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         TextField::new('title'),
    //         TextEditorField::new('description'),
    //     ];
    // }
    
}
