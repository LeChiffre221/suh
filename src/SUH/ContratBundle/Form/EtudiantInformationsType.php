<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtudiantInformationsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('required' => true))
            ->add('prenom', 'text', array('required' => true))
            ->add('mailInstitutionnel')
            ->add('mailPerso', 'text', array('required' => true))
            ->add('mailParents')
            ->add('adresseEtudiante')
            ->add('adresseFamiliale')
            ->add('telephonePerso')
            ->add('telephoneParents')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\GestionBundle\Entity\EtudiantInformations'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_gestionbundle_etudiantinformations';
    }
}
