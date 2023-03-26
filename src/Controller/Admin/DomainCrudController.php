<?php

namespace App\Controller\Admin;

use App\Entity\Domain;
use App\Repository\StationRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\User\UserInterface;

class DomainCrudController extends AbstractCrudController
{
    private StationRepository $stationRepository;

    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }
    public static function getEntityFqcn(): string
    {
        return Domain::class;
    }
    public function configureFields(string $pageName): iterable
    {
        $owner_id = $this->getUser()->getId();
        return [
            TextField::new('name'),
            TextField::new('description'),
            ImageField::new('icon_url')
                ->setBasePath('uploads/icons')
                ->setUploadDir('public/uploads/icons')
                ->setUploadedFileNamePattern('DomainIcon[randomhash].[extension]'),
            AssociationField::new('owner')->setFormTypeOptions([
                'query_builder' => function (UserRepository $er) use ($owner_id) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id = :id')
                        ->setParameter('id', $owner_id);
                }
            ])->addCssClass('d-none')->onlyWhenCreating(),
            AssociationField::new("stations"),
        ];
    }
}