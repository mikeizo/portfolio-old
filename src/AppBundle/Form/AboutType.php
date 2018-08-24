<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AboutType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('yearFrom', DateType::class, array(
					'required'	=> false,
					'html5'		=> false,
					'widget'	=> 'single_text',
					'attr'		=> array(
									'class' 		=> 'input-sm',
									'placeholder'	=> 'Year from'
								)
				))
				->add('yearTo', DateType::class, array(
					'required'	=> false,
					'html5'		=> false,
					'widget'	=> 'single_text',
					'attr'		=> array(
									'class' 		=> 'input-sm',
									'placeholder'	=> 'Year to'
								)
				))
				->add('description', TextareaType::class, array(
					'required'	=> false,
						'attr'	=> array(
									'class' 		=> 'summernote',
									'data-plugin' 	=> 'summernote',
									'data-air-mode' => 'true',
								),
				));
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\About'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_about';
	}

}
