<?php

namespace App\Controller\Admin;

use App\Entity\Station;
use App\Repository\UserRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Station::class;
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
        //si c'est pas un SU
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (!$this->isGranted('ROLE_SU')) {
            $queryBuilder->andWhere('entity.owner = :id');
            $queryBuilder->setParameter('id', $this->getUser()->getId());
        }
        return $queryBuilder;
    }

    public function configureFields(string $pageName): iterable
    {
        $owner_id = $this->getUser()->getId();
        return [
            TextField::new('name'),
            ImageField::new('icon_url')
                ->setBasePath('uploads/icons')
                ->setUploadDir('public/uploads/icons')
                ->setUploadedFileNamePattern('StationIcon[randomhash].[extension]'),
            AssociationField::new("domain")->onlyOnIndex(),
            TextareaField::new('description'),

            $this->isGranted('ROLE_SU') ? AssociationField::new('owner') :
                AssociationField::new('owner')->setRequired(true)->setFormTypeOptions([
                    'query_builder' => function (UserRepository $userRepository) use ($owner_id) {
                        return $userRepository->createQueryBuilder('u')
                            ->andWhere('u.id = :id')
                            ->setParameter('id', $owner_id);
                    }
                ]),
            ChoiceField::new("weather")->setChoices([
                'Sunny' => 'sunny',
                'Snowy' => 'snowy',
                'Cloudy'=> 'cloudy',
                'Rainy' => 'rainy',
                'Foggy' => 'foggy'
            ]),
            AssociationField::new("lifts")->hideWhenCreating()->autocomplete(),
            AssociationField::new("slopes")->hideWhenCreating()->autocomplete(),
        ];
    }
}
