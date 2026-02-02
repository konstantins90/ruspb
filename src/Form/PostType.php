<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название компании',
                'attr' => [
                    'placeholder' => 'Firmenname'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Улица, номер дома',
                'attr' => [
                    'placeholder' => 'Straße und Hausnummer'
                ]
            ])
            ->add('postcode', TextType::class, [
                'label' => 'Индекс',
                'attr' => [
                    'placeholder' => 'PLZ'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Город',
                'attr' => [
                    'placeholder' => 'Ort'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail',
                'attr' => [
                    'placeholder' => 'E-Mail'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Телефон',
                'attr' => [
                    'placeholder' => 'Telefon'
                ]
            ])
            ->add('web', TextType::class, [
                'label' => 'Сайт',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Webseite'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Beschreibung'
                ]
            ])
            ->add('category', CategoryAutocompleteFieldAdd::class, [
                'mapped' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Изображение',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста, загрузите изображение в формате JPEG, PNG или WEBP',
                    ])
                ],
            ])
            ->add('small_image', FileType::class, [
                'label' => 'Маленькое изображение',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста, загрузите изображение в формате JPEG, PNG или WEBP',
                    ])
                ],
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
