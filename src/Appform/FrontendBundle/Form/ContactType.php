<?php

namespace Appform\FrontendBundle\Form;

use Appform\FrontendBundle\Validator\Constraints\ExistEmail;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $departments = [
            'Administrator' => 'Administrator',
            'Accounting' => 'Accounting',
            'Recruitment' => 'Recruitment',
            'Services Information' => 'Products & Services',
            'Webmaster' => 'Webmaster'
        ];
        $builder
            ->setRequired(false)
            ->add('name', 'text', [
                'constraints' => [new NotBlank(['message' => 'Name is a required field'])
                ],
                'attr' => ['placeholder' => '* Your Name']
            ])
            ->add('email', 'email', [
                'constraints' => [
                    new NotBlank(['message' => 'Email is a required field']),
                    new Email(['message' => 'Please enter valid email address'])
                ],
                'attr' => ['placeholder' => '* Email']]
            )
            ->add('department', 'choice', [
                'choices' => $departments,
                'placeholder' => 'Select Department'
            ])
            ->add('message', 'textarea', ['attr' => ['placeholder' => 'Your Message']])
            ->add('submit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}
