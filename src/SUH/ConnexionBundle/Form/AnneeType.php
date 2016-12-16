<?php

namespace SUH\ConnexionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use SUH\GestionBundle\Entity\Annee;

class AnneeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anneeUniversitaire', 'integer')
            ->add('Ajouter',   'submit')
        ;
        //avant lenvoi, on modifie les donnees pour lenregistrement en base
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $data['anneeUniversitaire'] = $data['anneeUniversitaire'].'-'.($data['anneeUniversitaire'] + 1);
            $form->remove('anneeUniversitaire');
            $form->add('anneeUniversitaire', 'text');
            $event->setData($data);
            
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SUH\GestionBundle\Entity\Annee'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'suh_gestionbundle_annee';
    }
}
