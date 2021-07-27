<?php

namespace App\Controller\api\v1\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if (CRUD::PAGE_NEW === $pageName) {
            return [
                IdField::new('id')->onlyOnIndex(),
                TextEditorField::new('content'),
                DateTimeField::new('createdAt'),
                AssociationField::new('memini', 'Memini Id'),
            ];
        } else {
            return [
                IdField::new('id')->onlyOnIndex(),
                TextEditorField::new('content'),
                DateTimeField::new('createdAt'),
                AssociationField::new('memini', 'Memini Id')->onlyOnIndex(),    
            ];
        }
    }
    
}
