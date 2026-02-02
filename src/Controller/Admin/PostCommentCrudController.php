<?php

namespace App\Controller\Admin;

use App\Entity\PostComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PostComment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('post');
        yield TextField::new('name');
        yield IntegerField::new('rating');
        yield TextEditorField::new('message');
        yield BooleanField::new('isApproved');
        yield DateTimeField::new('createdAt');
    }
}
