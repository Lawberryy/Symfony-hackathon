<?php

namespace App\Controller\Admin;

use App\Entity\Lift;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LiftCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lift::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
           IdField::new('id')->hideOnForm(),
TextField::new('name'),
            DateField::new('first_hour')->setFormat('HH:mm'),
            DateField::new('last_hour')->setFormat('HH:mm'),
            DateField::new('peak_hour')->setFormat('HH:mm'),
            IntegerField::new('comfort'),


        ];
    }

}
