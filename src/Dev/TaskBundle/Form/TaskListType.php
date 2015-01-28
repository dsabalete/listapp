<?php

namespace Dev\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Lista',
                'required' => true, // client side only
                'error_bubbling' => true,
                'attr' => array(
                    'size' => '19',
                    'placeholder' => 'Nueva lista...',
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dev\TaskBundle\Entity\TaskList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dev_taskbundle_tasklist';
    }
}
