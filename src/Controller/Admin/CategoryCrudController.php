<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Категории')
            ->setPageTitle('new', 'Создать новую категорию')
            ->setPageTitle('detail', 'Категория')
            ->setPageTitle('edit', 'Категория')
            ->setEntityLabelInSingular('категорию')
            ->setEntityLabelInPlural('категории');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name', 'Название');
        yield Field::new('imageFile', 'Изображение')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();
        yield ImageField::new('imageUrl', 'Изображение')
            ->onlyOnIndex();
    }
}
