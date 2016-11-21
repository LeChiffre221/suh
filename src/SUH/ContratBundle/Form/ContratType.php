<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('natureContrat',            'textarea')
            ->add('nbHeureInitiales',         'number', array('pattern' => '[0-9]+'))
            ->add('dateDebutContrat',         'date', array('pattern' => '^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$', ))
            ->add('dateFinContrat',           'date', array('pattern' => '^[0-9]{2}[/]{1}[0-9]{2}[/]{1}[0-9]{4}$', ))
            ->add('semestreConcerne',         'number', array('pattern' => '1|2{1}'))
            ->add('etablissementAvenant',     'checkbox', array('required' => false))

            ->add('Ajouter',      'submit')


        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\ContratBundle\Entity\Contrat'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_contratbundle_contrat';
    }
}
