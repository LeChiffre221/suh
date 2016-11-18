<?php

namespace SUH\ContratBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
                ));

        //On empeche la verification du champ autre pour pouvoir y injecter notre value personnalisee
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $form->remove('diplome');
            $form->add('diplome', 'choice', array(
                'attr' => array(
                    'autre' => 'diplome',
                ),
                'choices' => array(
                    $data['diplome'] => $data['diplome'],
                )
            ));
        });
        $builder
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
        ;
        //On empeche la verification du champ autre pour pouvoir y injecter notre value personnalisee
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $form->remove('etablissement');
                $form->add('etablissement', 'choice', array(
                    'attr' => array(
                        'autre' => 'etablissement',
                    ),
                    'choices' => array(
                        $data['etablissement'] => $data['etablissement'],
                    )
                ));
            })
        ;
        $builder
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
