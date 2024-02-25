<?php

namespace App\Form;

use App\Entity\CentreRecuperation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreRecuperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Sigle', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CentreRecuperation::class,
        ]);
    }
}
