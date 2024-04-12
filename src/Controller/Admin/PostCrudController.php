<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield AssociationField::new('category');
        yield TextField::new('subname');
        yield IntegerField::new('status');
        yield TextEditorField::new('description');
        yield TextField::new('address');
        yield TextField::new('postcode');
        yield TextField::new('city');
        yield EmailField::new('email');
        yield TelephoneField::new('phone');
        yield UrlField::new('web');
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
