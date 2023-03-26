<?php

namespace App\Controller\Admin;

use App\Entity\Slope;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class SlopeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slope::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            IntegerField::new('difficulty'),
            IntegerField::new('snow_quality'),
            DateField::new('first_hour')->setFormat('HH:mm'),
            DateField::new('last_hour')->setFormat('HH:mm'),
            DateField::new('peak_hour')->setFormat('HH:mm'),
        ];
    }

}
