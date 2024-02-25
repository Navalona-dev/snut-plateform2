<?php

namespace App\Form;

use App\Entity\DataCrenas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataCrenasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('besoinAPTE')
            ->add('besoinAMOX')
            ->add('besoinFichePatient')
            ->add('besoinRegistre')
            ->add('quantite01AmoxSDUCartonBSD')
            ->add('dateExpiration01AmoxSDUCartonBSD')
            ->add('quantite02AmoxSDUCartonBSD')
            ->add('dateExpiration02AmoxSDUCartonBSD')
            ->add('quantite03AmoxSDUCartonBSD')
            ->add('dateExpiration03AmoxSDUCartonBSD')
            ->add('quantite04AmoxSDUCartonBSD')
            ->add('dateExpiration04AmoxSDUCartonBSD')
            ->add('totalAmoxSDUCartonBSD')
            ->add('quantite01AmoxSDUCartonCSB')
            ->add('dateExpiration01AmoxSDUCartonCSB')
            ->add('quantite02AmoxSDUCartonCSB')
            ->add('dateExpiration02AmoxSDUCartonCSB')
            ->add('quantite03AmoxSDUCartonCSB')
            ->add('dateExpiration03AmoxSDUCartonCSB')
            ->add('quantite04AmoxSDUCartonCSB')
            ->add('dateExpiration04AmoxSDUCartonCSB')
            ->add('totalAmoxSDUCartonCSB')
            ->add('totalAmoxSDUCartonSDSP')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DataCrenas::class,
        ]);
    }
}
