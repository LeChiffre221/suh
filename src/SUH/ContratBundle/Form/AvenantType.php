<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AvenantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nbHeure',                  'number', array('pattern' => '[0-9]+'))
            ->add('dateEnvoiDRH',           'text', array('pattern' => '^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$', ))
            ->add('dateEnvoiEtudiant',      'text', array('pattern' => '^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$', ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\ContratBundle\Entity\Avenant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_contratbundle_avenant';
    }
}
