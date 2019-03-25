<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class OrderAdmin extends AbstractAdmin
{
    // Manage toolset
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('create');
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id')
                       ->add('customer_name')
                       ->add('phone_number')
                       ->add('email')
                       ->add('address')
                       ->add('total_sum')
                       ->add('is_complete');
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id')
                   ->add('customer_name')
                   ->add('phone_number')
                   ->add('email')
                   ->add('address')
                   ->add('total_sum', 'currency', [
                        'currency' => 'UAH'
                    ])
                    ->add('ordered_products', 'sonata_type_model_list', [
                        'btn_add'       => false,
                        'btn_delete'    => false,
                    ])
                   ->add('is_complete', 'choice', [
                        'editable' => true,
                        'choices' => [
                            false => 'Processing',
                            true => 'Complete',
                        ],
                    ]);
    }
}