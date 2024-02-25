<?php

namespace App\Form;

use App\Entity\AnneePrevisionnelle;
use App\Entity\CommandeTrimestrielle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeTrimestrielleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebut')
            ->add('DateFin')
            ->add('isActive')
            ->add('AnneePrevisionnelle', EntityType::class, [
                'class' => AnneePrevisionnelle::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.Annee', 'ASC');
                },
                'choice_label' => 'Annee',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir année',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Slug', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandeTrimestrielle::class,
        ]);
    }
}
