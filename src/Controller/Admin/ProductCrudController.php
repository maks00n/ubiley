<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Enum\EnumWeekday;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Продукты')
            ->setPageTitle('new', 'Создать новый продукт')
            ->setPageTitle('detail', 'Продукты')
            ->setPageTitle('edit', 'Продукты')
            ->setEntityLabelInSingular('продукт')
            ->setEntityLabelInPlural('продукты');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name', 'Название');
        yield BooleanField::new('stopList', 'Стоп-лист');
        yield ChoiceField::new('weekday', 'День недели')
            ->setFormTypeOptions([
                'choice_label' => fn(EnumWeekday $e) => $e->value,
            ])
            ->formatValue(function ($value) {
                return $value instanceof EnumWeekday ? $value->value : null;
            });
        yield MoneyField::new('price', 'Цена')
            ->setCurrency('RUB');
        yield AssociationField::new('category', 'Категория');
        yield Field::new('imageFile', 'Изображение')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();
        yield ImageField::new('imageUrl', 'Изображение')->onlyOnIndex();
    }
}
