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
            ->setPageTitle('new', 'Создать новый стол')
            ->setPageTitle('detail', 'Стол')
            ->setPageTitle('edit', 'Стол')
            ->setEntityLabelInSingular('стол')
            ->setEntityLabelInPlural('столы');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield IntegerField::new('num', 'Номер стола');
        yield TextField::new('description', 'Описание');
        yield IntegerField::new('maxGuests', 'Макс количество человек');
        yield IntegerField::new('guestsDef', 'Гостей')->onlyOnIndex();
        yield IntegerField::new('guestsNow', 'Присутствует гостей')->onlyOnIndex();
    }
}
