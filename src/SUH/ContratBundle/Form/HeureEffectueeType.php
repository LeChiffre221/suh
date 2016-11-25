<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HeureEffectueeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('natureMission', 'choice', array(
                'choices' => array(
                    'tutorat' => 'Tutorat',
                    'priseNote' => 'Prise de note',
                    'assistancePédagogique' => 'Assistance Pédagogique'
                )
            ))
            ->add('descriptionMission', 'textarea', array('required' => false))
            ->add('dateAndTime', 'text')
            ->add('nbHeure', 'number', array('pattern' => '[0-9]+'))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\ContratBundle\Entity\HeureEffectuee'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_contratbundle_heureeffectuee';
    }
}
