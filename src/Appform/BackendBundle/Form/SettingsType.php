<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setRequired(false);
        $settings = array(
            'webSite' => new WebSiteSettingType()
        );

        foreach ($settings as $settingName => $settingValue) {
            if (empty($options['specific_settings']) || in_array($settingName, $options['specific_settings'])) {
                $builder->add($settingName, $settingValue);
            }
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\BackendBundle\Entity\Settings',
            'specific_settings' => array(),
            'attr' => array(
                'class' => 'form form-horizontal',
            )
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_settings';
    }
}
