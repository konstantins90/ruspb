<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class CategoryAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Категория',
            'class' => Category::class,
            'placeholder' => 'Поиск по категориям',
            'searchable_fields' => ['name', 'slug', 'description', 'synonym', 'synonym_de'],
            'choice_label' => 'name',
            'choice_value' => 'id',
        ]);

        $resolver->setDefined(['extra_options']);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
