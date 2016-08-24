<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class TwittType extends AbstractType
{
    
	public function getName()
	{
		return 'post_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('twitt_text', TextType::class, array(
                'label' => 'Twitt Text'))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Twitt'));
    }
}