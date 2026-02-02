<?php

namespace App\Form;

use App\Entity\PostComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PostCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new NotBlank(['message' => 'Bitte Name angeben.']),
                ],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Bewertung',
                'choices' => [
                    '5' => 5,
                    '4' => 4,
                    '3' => 3,
                    '2' => 2,
                    '1' => 1,
                ],
                'expanded' => false,
                'placeholder' => 'Bitte auswählen',
                'constraints' => [
                    new NotBlank(['message' => 'Bitte Bewertung auswählen.']),
                    new Range(['min' => 1, 'max' => 5]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Комментарий',
                'constraints' => [
                    new NotBlank(['message' => 'Bitte Kommentar schreiben.']),
                ],
                'attr' => [
                    'rows' => 4,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostComment::class,
        ]);
    }
}
