<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class, array(
				'label' => false,
				'attr' 	=> array(
							'placeholder'		=> 'Name',
							'ng-model'			=> 'contactData.name',
							'ng-minlength'		=> '2',
							'ng-maxlength'		=> '50',
							'ng-model-options'	=> '{ updateOn: "blur" }'
						),
			))
			->add('email', EmailType::class, array(
				'label'	=> false,
				'attr'	=> array(
							'placeholder'		=> 'Email',
							'ng-model'			=> 'contactData.email',
							'ng-model-options'	=> '{ updateOn: "blur" }'
						),
			))
			->add('phone', TextType::class, array(
				'required'	=> false,
				'label'		=> false,
				'attr'		=> array(
								'placeholder'		=> 'Phone',
								'ng-model'			=> 'contactData.phone',
								'ng-maxlength'		=> '14',
								'ng-pattern'		=> '/^[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
								'ng-model-options'	=> '{ updateOn: "blur" }'
							),
			))
			->add('comments', TextareaType::class, array(
				'label'		=> false,
				'required'	=> false,
				'attr'		=> array(
								'placeholder'	=> 'Comments',
								'ng-model'		=> 'contactData.comments',
								'ng-maxlength'	=> "300",
							),
			))
		;
	}
	
}