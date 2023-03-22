<?php

namespace App\Controller\Admin;

use App\Entity\Station;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Station::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $owner_id = $this->getUser()->getId();
        return [
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('icon_url'),
            AssociationField::new('owner')->setFormTypeOptions([
                'query_builder' => function (UserRepository $er) use ($owner_id) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id = :id')
                        ->setParameter('id', $owner_id);
                }
            ])->addCssClass('d-none')->onlyWhenCreating(),
        ];
    }
}
