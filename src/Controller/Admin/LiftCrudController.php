<?php

namespace App\Controller\Admin;

use App\Entity\Lift;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class LiftCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lift::class;
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
                AssociationField::new('station')->setRequired(true)->setFormTypeOptions([
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.owner = :id')
                        ->setParameter('id', $this->getUser()->getId());
                }]),
            ChoiceField::new("type")->setChoices([
                'chairlift' => 'chairlift',
                'gondola' => 'gondola',
                'drag lift' => 'drag lift',
            ])->setRequired(true),
            TimeField::new('first_hour')->setFormat('HH:mm'),
            TimeField::new('last_hour')->setFormat('HH:mm'),
            TimeField::new('peak_hour')->setFormat('HH:mm'),
            IntegerField::new('comfort')->setHelp('1: no comfort, 5: very comfortable')->setFormTypeOptions(['attr' => ['min' => 1, 'max' => 5]])->hideOnIndex(),
            BooleanField::new('exception'),
            TimeField::new('duration')->setFormat('HH:mm:ss'),
            TextField::new('exception_message'),
        ];
    }

}
