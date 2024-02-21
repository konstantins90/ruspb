<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название компании'
            ])
            ->add('subname')
            ->add('address', TextType::class, [
                'label' => 'Улица, номер дома'
            ])
            ->add('postcode', TextType::class, [
                'label' => 'Индекс'
            ])
            ->add('city', TextType::class, [
                'label' => 'Город'
            ])
            ->add('description')
            ->add('category', CategoryAutocompleteField::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
