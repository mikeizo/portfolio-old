<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', TextType::class, array(
					'required' 	=> false
				))
				->add('description', TextareaType::class, array(
					'required'	=> false,
					'attr'		=> array('rows' => '5')
				))
				->add('shortDescription', TextType::class, array(
					'required'	=> false,
				))
				->add('resources', TextType::class, array(
					'required'	=> false,
				))
				->add('url', TextType::class, array(
					'required'	=> false,
				))
				->add('logo', FileType::class, array(
					'required'	=> false,
					'data_class'=> null,
				))
				->add('image', FileType::class, array(
					'required'	=> false,
					'data_class'=> null,
				))
				->add('color', TextType::class, array(
					'required' 	=> false,
					'attr' 		=> array(
									'class' 		=> 'minicolors',
									'data-control' 	=> 'hue'
								),
				));
				//->add('slug')
				//->add('position')
				//->add('date')
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Project'
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
