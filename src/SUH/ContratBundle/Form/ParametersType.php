<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParametersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adminMail',           'text')
            ->add('portMail',           'text')
            ->add('hostMail',           'text')
            ->add('usernameMail',           'text')
            ->add('passwordMail',           'password', array('always_empty' => false, 'required' => false))
            ->add('prixHoraire',         'number')
            ->add('coefTutorat',         'number')
            ->add('coefPriseDeNote',         'number')
            ->add('coefAssistance',         'number')
            ->add('Envoyer', 'submit')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\ContratBundle\Entity\Parameters'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_contratbundle_parameters';
    }
}
