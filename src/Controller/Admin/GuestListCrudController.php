<?php

namespace App\Controller\Admin;

use App\Entity\GuestList;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GuestListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GuestList::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Гости')
            ->setPageTitle('new', 'Создать нового гостя')
            ->setPageTitle('detail', 'Гости')
            ->setPageTitle('edit','Гости')
            ->setEntityLabelInSingular('гостя')
            ->setEntityLabelInPlural('гости');
    }

    public function configureFields(string $pageName): iterable
    {
            yield IdField::new('id')->onlyOnIndex();
            yield BooleanField::new('isPresent', 'Присутствие');
            yield TextField::new('name', 'ФИО');
            yield AssociationField::new('tables', 'Стол');
    }
}
