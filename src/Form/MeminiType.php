<?php

namespace App\Form;

use App\Entity\Memini;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MeminiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, ["constraints" => [new NotBlank()]])
            ->add('public', null, ["constraints" => [new NotBlank()]])
            ->add('picture')
            ->add('sendAt', null, [
                'widget' => 'single_text'
            ])
            ->add('user', null, ["constraints" => [new NotBlank()]])
            ->add('tag')
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Memini::class,
        ]);
    }
}
