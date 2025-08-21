<?php

namespace App\Controller\Admin;

use App\Entity\Tables;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TablesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tables::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Столы')
            ->setPageTitle('new', 'Создать новый Стол')
            ->setPageTitle('detail', 'Стол')
            ->setPageTitle('edit','Стол')
            ->setEntityLabelInSingular('Стол')
            ->setEntityLabelInPlural('Столы');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            IntegerField::new('num', 'Номер стола'),
            TextField::new('description', 'Описание'),
            IntegerField::new('maxGuests', 'Макс количество человек'),
            IntegerField::new('guestsDef', 'Гостей')->onlyOnIndex(),
            IntegerField::new('guestsNow', 'Присутствует гостей')->onlyOnIndex(),
        ];
    }
}
