<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class FormationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('diplome', 'choice', array(
                'choices' => array(
                    'Licence' => 'Licence',
                    'DUT' => 'DUT',
                    'Master' => 'Master',
                    'Doctorat' => 'Doctorat',
                    'Concours' => 'Concours',
                    'DAEU' => 'DAEU',
                    'autre' => 'Autre',
                )
                ))
            ->add('composante')
            ->add('filiere')
            ->add('cycle')
            ->add('etablissement', 'choice', array(
                'choices' => array(
                    'UBP' => 'UBP',
                    'UDA' => 'UDA',
                    'UCA' => 'UCA',
                    'Sigma' => 'Sigma',
                    'Vetagrosup' => 'Vetagrosup',
                    'Ensacf' => 'Ensacf',
                    'autre' => 'Autre',
                )
                ))
            ->add('anneeEtude')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\GestionBundle\Entity\Formation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_gestionbundle_formation';
    }
}
