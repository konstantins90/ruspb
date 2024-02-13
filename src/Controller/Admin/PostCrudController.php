<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield TextField::new('subname');
        yield TextEditorField::new('description');
        yield TextField::new('address');
        yield TextField::new('postcode');
        yield TextField::new('city');
        yield AssociationField::new('category');
        yield ImageField::new('image')
            ->setBasePath('/uploads/posts/')
            ->setUploadDir('/public/uploads/posts/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);
        yield ImageField::new('small_image')
            ->setBasePath('/uploads/posts/')
            ->setUploadDir('/public/uploads/posts/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);
    }
}
