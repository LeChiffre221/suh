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
            ->add('dateDebutContrat',         'text')
            ->add('dateFinContrat',           'text')
            ->add('semestreConcerne',         'number', array('pattern' => '1|2{1}'))
            //->add('dateEnvoiDRH',             'date')
            //->add('dateEnvoiEtudiant',        'date')
            //->add('etablissementAvenant',     'checkbox')
            //->add('dateEnvoiAvenantDRH',      'date')
            //->add('dateEnvoiAvenantEtudiant', 'date')
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
