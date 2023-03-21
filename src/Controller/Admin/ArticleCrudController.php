<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('author');

        yield AssociationField::new('category');

        yield TextField::new('title');

        yield SlugField::new('slug')
            ->setTargetFieldName('title');

        yield TextEditorField::new('content');

        yield TextField::new('featuredText', 'Texte mis en avant');

        yield ImageField::new('featuredImage')
            ->setUploadDir('public/article-img/')
            ->setUploadedFileNamePattern('[year]/[month]/[day]/[slug]-[contenthash].[extension]');

        yield IntegerField::new('status');

        yield DateTimeField::new('createdAt')
            ->hideOnForm();
        
        yield DateTimeField::new('updatedAt')
            ->hideOnForm();

    }
}
