<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

class WebSiteSettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setByReference(false)
            ->setRequired(false)
            ->add('name', 'text', array(
                    'label' => 'Site Name'
            ))
            ->add('email', 'text', array(
                    'label' => 'Site Email',
                    'constraints' => array(
                        new Email()
                    )
            ))
            ->add('subject', 'text', array(
                'label' => 'Subject template'
            ))
            ->add('ipForBan', 'text', [
                'attr' => [
                    'class' => 'tagsinput',
                    'placeholder' => 'Add Ip',
                    'label' => 'Ip for ban'
                ]
            ])
            ->add('domainForBan', 'text', [
                'attr' => [
                    'class' => 'tagsinput',
                    'placeholder' => 'Add domain',
                ],
                'label' => 'Domain for ban'
            ])
            ->add('saveWebSiteSettings', 'submit', array(
                'label' => 'Save'
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\BackendBundle\Entity\Settings\WebSiteSetting',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_websitesettings';
    }
}
