<?php

namespace App\Controller\Admin;

use App\Entity\Problem;
use App\Controller\Admin\StationCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProblemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Problem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['station' => 'ASC', 'date' => 'DESC']); // trier par station croissant et date décroissant
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('station')->setCrudController(StationCrudController::class);
        yield TextField::new('title');
        yield TextareaField::new('description');
        yield DateTimeField::new('date');
    }
}
