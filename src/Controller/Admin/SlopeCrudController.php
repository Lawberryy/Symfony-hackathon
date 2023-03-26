<?php

namespace App\Controller\Admin;

use App\Entity\Slope;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class SlopeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slope::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getUser()->getRoles()[0] == "ROLE_SU") {
            $actions->remove(Crud::PAGE_INDEX, Action::NEW);
            $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        }

        return $actions;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if(!$this->isGranted('ROLE_SU')){
            $queryBuilder->join('entity.station', 's');
            $queryBuilder->andWhere('s.owner = :id');
            $queryBuilder->setParameter('id', $this->getUser()->getId());
        }
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            $this->isGranted('ROLE_SU') ? AssociationField::new('station') :
                AssociationField::new("station")->setRequired(true)->setFormTypeOptions([
                    'query_builder' => function ($er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.owner = :id')
                            ->setParameter('id', $this->getUser()->getId());
                    }
                ]),
            IntegerField::new("difficulty")->setHelp('1: green, 2: blue, 3: red, 4: black')->setFormTypeOptions(['attr' => ['min' => 1, 'max' => 4]])->setRequired(true),
            TimeField::new('first_hour')->setFormat('HH:mm'),
            TimeField::new('last_hour')->setFormat('HH:mm'),
            TimeField::new('peak_hour')->setFormat('HH:mm')->hideOnIndex(),
            IntegerField::new('snow_quality')->setFormTypeOptions(['attr' => ['min' => 1, 'max' => 5]])->setHelp('1: no snow, 5: very good snow')->setRequired(true),
            BooleanField::new('exception'),
            TextField::new('exception_message'),

        ];
    }

}
