<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ConfigType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('headline', TextareaType::class, array(
					'required' 	=> false,
					'attr'		=> array(
									'class'			=> 'summernote',
									'data-plugin' 	=> 'summernote',
									'data-air-mode' => 'true',
								),
				))
				->add('about', TextareaType::class, array(
					'required' 	=> false,
					'attr'		=> array(
									'class'			=> 'summernote',
									'data-plugin' 	=> 'summernote',
									'data-air-mode' => 'true',
								),
				))
				->add('email', TextType::class, array(
					'required'	=> false,
				))
				->add('analytics', TextareaType::class, array(
					'required' 	=> false,
					'attr'		=> array('rows' => '15')
				))
				->add('quote', TextareaType::class, array(
					'required' 	=> false,
					'attr'		=> array(
									'class'			=> 'summernote',
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
			'data_class' => 'AppBundle\Entity\Config'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_projects';
	}

}
