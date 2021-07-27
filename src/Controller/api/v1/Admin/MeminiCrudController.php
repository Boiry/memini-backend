<?php

namespace App\Controller\api\v1\Admin;

use App\Entity\Memini;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MeminiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Memini::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if (CRUD::PAGE_NEW === $pageName) {
            return [
                AssociationField::new('user', 'userId'),
                TextField::new('content'),
                ImageField::new('picture')
                    ->setUploadDir('public/'),
                TextField::new('tag'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('sendAt'),
                BooleanField::new('isSent'),
                BooleanField::new('public'),
            ];
        } else {
            return [
                AssociationField::new('user', 'userId')->onlyOnIndex(),
                TextField::new('content'),
                ImageField::new('picture')
                    ->setUploadDir('public/'),
                TextField::new('tag'),
                DateTimeField::new('createdAt'),
                DateTimeField::new('sendAt'),
                BooleanField::new('isSent'),
                BooleanField::new('public'),
            ];
        }
    }
}
